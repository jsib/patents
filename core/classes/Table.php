<?php

namespace Core;

use Core\Database;
use Core\View;
use Core\Auth;
use Core\Request;

/**
 * Provide machinery for easy HTML tables creating
 */
abstract class Table
{
    use TableDefinition;
    
    /**
     * Store database object here.
     * We make injection of this object in __construct() method.
     */
    protected $db;
    
    /**
     * Store information about authentication
     */
    public $auth;
    
    /**
     * Data, which we retrieve from database.
     * See getData() method description for deeply understanding how we use it.
     */
    protected $data = [];
    
    /*
     * Data from $data property, which was prepared with prepareData() method.
     */
    protected $matrix = [];
    
    /**
     * Appearance of table. Contain info about style, onclick attributes, etc.
     */
    protected $appearance = [];
    
    /**
     * Store links for table
     */
    protected $links = [];
    
    /**
     * Single height for all rows in table
     */
    protected $rowHeight = '';
    
    /**
     * Single border width for the whole table
     */
    protected $border;
    
    /**
     * Columns headers (first row) data
     */
    protected $headers;

    /**
     * Columns properties
     */
    protected $columns;

    
    /**
     * Sort table by this column
     */
    protected $sort;
    
    /**
     * Sort direction for table sorting
     */
    protected $sortDirection;


    final public function __construct()
    {
        //Make injections
        $this->db = new Database();
        $this->view = new View();
        $this->auth = new Auth();
        $this->request = new Request();
    }


    
    /**
     * Add information about the header to $headers property.
     */
    final protected function addHeaderColumn($column, $value)
    {
        $this->headers[$column] = $value;
    }
    
    /**
     * Add information about column type to $columns property.
     */
    final protected function setColumnType($column, $type)
    {
        $this->columns[$column]['type'] = $type;
    }

    /**
     * Get column type
     */
    final public function getColumnType($column)
    {   
        if ( !isset($this->columns[$column]['type']) ) {
            return '';
        }
        
        return $this->columns[$column]['type'];
    }
    
    /**
     * Add information about column width to $columns property.
     */
    final protected function setColumnWidth($column, $width)
    {
        $this->columns[$column]['width'] = $width;
    }
    
    /**
     * Get column width
     */
    final public function getColumnWidth($column)
    {   
        if ( !isset($this->columns[$column]['width']) ) {
            return '';
        }
        
        return $this->columns[$column]['width'];
    }

    /**
     * Add information about column width to $columns property.
     * Be patient, that input width expand width of columns
     */
    final protected function setColumnInputWidth($column, $width)
    {
        $this->columns[$column]['input-width'] = $width;
    }
    
    /**
     * Get column input width
     */
    final public function getColumnInputWidth($column)
    {   
        if ( !isset($this->columns[$column]['input-width']) ) {
            return '';
        }
        
        return $this->columns[$column]['input-width'];
    }
    
    /**
     * Set single height for all rows
     */
    final protected function setRowHeight($height)
    {
        $this->rowHeight = $height;
    }
    
     /**
     * Get cell appearance
     */
    final public function getCellAppearance($row, $column)
    {   
        if ( !isset($this->matrix[$row][$column]) ) {
            return '';
        }
        
        return $this->matrix[$row][$column];
    }
   
    
    /**
     * Set table's border width
     * 
     * @border int Width of border in pixels
     */
    final protected function setBorderWidth($border)
    {
        $this->border = $border;
    }

    /**
     * Produce html for column header, including functionality for sorting
     */
    final public function buildColumnHeader(
        $column_name,
        $column_name_rus,
        $sort_column,
        $sort_direction
    ){
        //Get new sort dirction and new opposite sort direction
        if($sort_column == $column_name){
            if($sort_direction == "asc"){
                $sort_direction_new = "desc";
                $sort_direction_opposite_new = "asc";
            }else{
                $sort_direction_new = "asc";
                $sort_direction_opposite_new = "desc";
            }
        }else{
            $sort_direction_new = "asc";
            $sort_direction_opposite_new = "asc";
        }

        //Change 'sort' argument in uri
        $uri = uri_make('sort', $column_name);

        //Change 'sort_direction' argument in uri
        $uri = uri_change('sort_direction', $sort_direction_new, $uri);

        //Build html links for sorting
        $hrefs = "<a href='" . $uri . "' class='sort'>" . $column_name_rus . "</a>";

        //Build additional links with arrow icon
        if($sort_column == $column_name){
            $hrefs .= "
                <a href='" . $uri . "'>
                    <img src='/_content/img/".$sort_direction_opposite_new.".png' 
                        style='margin:0 0 0 3px;'/>
                </a>";
        }

        return $hrefs;
    }

    /**
     * Build ready for use table's html
     */
    final public function build(){
        $this->getData();
        $this->setHeader();
        $this->setColumns();
        $this->prepareData();
        $this->setOtherProperties();
        
        //Configure sorting parameters
//        if(!$sort=get_sort()) $sort=$table['sort_default'];
//        if(!$sort_direction=get_sort_direction()) $sort_direction=$table['sort_direction_default'];
//        $table['sort']=$sort;
//        $table['sort_direction']=$sort_direction;
        
        //Sort table
//        table_matrix_sort($table['matrix'], $sort, $sort_direction, $table['sort_specific']);
        
        if( $this->auth->getRight('edit') ){
		$form_action = uri_make('action', 'save_patent');
	}else{
		$form_action = uri_make();
	}

        
        return $this->view->load('table', [
            'headers' => $this->headers,
            'columns' => $this->columns,
            'matrix' => $this->matrix,
            'appearance' => $this->appearance,
            'links' => $this->links,
            'border' => $this->border,
            'sort' => $this->sort,
            'sort_direction' => $this->sortDirection,
            'form_action' => $form_action,
            'table' => $this,
        ]);
    }    
}

