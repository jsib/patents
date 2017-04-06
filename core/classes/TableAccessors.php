<?php

namespace Core;

/**
 * This trait provide all setters and getters for table building.
 */
trait TableAccessors
{
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
     * Set default sort column
     */
    final protected function setDefaultSortColumn($column)
    {
        $this->defaultSortColumn = $column;
    }
    
    /**
     * Set default sort direction
     */
    final protected function setDefaultSortDirection($direction)
    {
        $this->defaultSortDirection = $direction;
    }
}
