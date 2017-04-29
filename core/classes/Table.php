<?php

namespace Core;

use Core\Database;
use Core\View;
use Core\Auth;
use Core\Request;

/**
 * Table class provides machinery for easy HTML tables creating
 */
abstract class Table extends Base
{
    use TableDefinition;
    use TableAccessors;
    use TableSort;
    
    /**
     * @var object $auth Store information about authentication
     * @var string $formAction Action attribute of a form
     */
    public $auth, $formAction;
    
    /**
        @var array $data Table data, which we retrieve from database
        @var array $matrix Table data, prepared to pass to sort
        @var array $appearance Table cell appearance properties
        @var array $links Hyperlinks in table
        @var integer $rowHeight Height of all table's rows in pixels
        @var integer $border Inside table borders width in pixels
        @var array $headers  Table's headers properties
        @var array $columns Table's columns properties
        @var boolean $dontUseSorting If true, then we don't need to sort table
        @var string $sortColumn Sort table by this column
        @var string $sortDirection Sort table in this direction
        @var string $defaultSortColumn Default sort column for table
        @var string $defaultSortDirection Default sort direction for table
     * @var string $viewFile Table's view file
     */
    protected $data, $matrix, $appearance, $links, $rowHeight,
        $border, $headers, $columns, $sortColumn, $sortDirection,
        $dontUseSorting = false, $defaultSortColumn = false,
        $defaultSortDirection = false, $uri, $viewFile = '';
    
    final public function __construct()
    {
        //Inherit constuctor from parent
        parent::__construct();
        
        //Make injections
        $this->auth = $GLOBALS['auth'];
        $this->request = new Request();
        $this->uri = $GLOBALS['uri'];
    }

    /**
     * Add information about the header to $headers property.
     */
    final protected function addHeaderColumn($column, $value)
    {
        $this->headers[$column] = $value;
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
        $uri = $this->uri->uriMake('sort', $column_name);

        //Change 'sort_direction' argument in uri
        $uri = $this->uri->uriChange('sort_direction', $sort_direction_new, $uri);

        //Build html links for sorting
        $hrefs = "<a href='" . $uri . "' class='sort'>" . $column_name_rus . "</a>";

        //Build additional links with arrow icon
        if($sort_column == $column_name){
            $hrefs .= "
                <a href='" . $uri . "'>
                    <img src='".ASSETS_PATH."img/".$sort_direction_opposite_new.".png' 
                        style='margin:0 0 0 3px;'/>
                </a>";
        }

        return $hrefs;
    }
    
    /**
     * Build ready for use table's html
     */
    final public function build(){
        //Define headers properties
        $this->setHeader();
        
        //Define columns properties
        $this->setColumns();
        
        //Set other table properties
        $this->setOtherProperties();

        //Get data for table from database
        $this->getData();
        
        //Build array to use in view
        $this->prepareData();
        
        //Get sort column and direction or sorting is not needed
        if ($this->defaultSortDirection !== false ) {
            $this->getSortColumn();
            $this->getSortDirection();
        }
        
        //Sort table's data
        if ($this->dontUseSorting === false) {
            $this->sortData();
        }
        
        //Check if view defined
        if ($this->viewFile === '') {
            error("View file for table is not defined");
        }
        
        return $this->view->load($this->viewFile, [
            'headers' => $this->headers,
            'rowHeight' => $this->rowHeight,
            'columns' => $this->columns,
            'matrix' => $this->matrix,
            'appearance' => $this->appearance,
            'links' => $this->links,
            'border' => $this->border,
            'sortColumn' => $this->sortColumn,
            'sortDirection' => $this->sortDirection,
            'form_action' => $this->formAction,
            'table' => $this,
        ]);
    }    
}

