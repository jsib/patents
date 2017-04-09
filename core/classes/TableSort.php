<?php

namespace Core;

/**
 * Methods needed, to sort table
 */
trait TableSort
{
    /**
     * Get column for sorting table
     */
    function getSortColumn(){
        //Client didn't specify any sort column
        if( !isset($_GET['sort']) ) {
            $this->sortColumn = $this->defaultSortColumn;
            return;
        }
        
        //Prevent malicious code from client
        if( !\preg_match("/^[A-z0-9_]{1,30}$/", $_GET['sort']) ) {
            $this->sortColumn = $this->defaultSortColumn;
            return;
        }
        
        //Client explicitly specify sort direction
        $this->sortColumn = $_GET['sort'];
    }

    /**
     * Get sort direction for sorting table
     */
    function getSortDirection(){
        //Client didn't specify any sort direction
        if ( !isset($_GET['sort_direction']) ) {
            $this->sortDirection = $this->defaultSortDirection;
            return;
        }
        
        //Prevent malicious code from client
        if( !\preg_match("/^(asc|desc)$/", $_GET['sort_direction']) ){
            $this->sortDirection = $this->defaultSortDirection;
            return;
        }
        
        //Client explicitly specify sort direction
        $this->sortDirection = $_GET['sort_direction'];
    }

    /**
     * Sort table's matrix
     */
    function sortData(){
        //Get prepared table's data
        $matrix = $this->matrix;
        
        //Do nothing, if matrix is empty
        if( count($matrix) == 0 ) {
            return;
        }
        
        //Create expanded matrix
        foreach($matrix as $key => $columns) {
            $matrix_sort[$key] = $columns[$this->sortColumn];
        }

        //For special data types perform special sorting
        if ( isset($this->sortSpecific[$this->sortColumn]) ) {
            switch ( $this->sortSpecific[$this->sortColumn] ) {
                //For ip addresses
                case 'ip':
                    switch ($this->sortDirection)
                    {
                        case 'asc':
                            sort_ips($matrix_sort, $this->sortDirection);
                            break;
                        case 'desc':
                            sort_ips($matrix_sort, $this->sortDirection);
                            break;
                    }
                break;
            }
        //For not special data types perform usual sorting
        } else {
            switch ($this->sortDirection)
            {
                case 'asc':
                    asort($matrix_sort);
                break;
                case 'desc':
                    arsort($matrix_sort);
                break;
            }
        }
        
        //Contains empty and all elements
        $matrix_empty = [];
        $matrix_full = [];

        //Put all empty elements at the beginning or at the end
        foreach($matrix_sort as $key => $value){
            if( trim($value) == "" ){
                $matrix_empty[$key] = $value;
            }
            
            if( trim($value) != "") {
                $matrix_full[$key] = $value;
            }
        }

        //Build new matrix
        switch($this->sortDirection){
            case 'asc':
                foreach( (array) $matrix_full as $key => $empty ){
                    $matrix_new[$key]=$matrix[$key];
                }
                foreach( (array)$matrix_empty as $key => $empty ){
                    $matrix_new[$key]=$matrix[$key];
                }
            break;
            case 'desc':
                foreach( (array)$matrix_empty as $key => $empty ){
                    $matrix_new[$key]=$matrix[$key];
                }
                foreach( (array)$matrix_full as $key => $empty ){
                    $matrix_new[$key]=$matrix[$key];
                }
                break;
        }

        //Change source matrix to new one
        $this->matrix = $matrix_new;
    }    
}

