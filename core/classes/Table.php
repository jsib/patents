<?php

/**
 * Provide machinery for easy HTML tables creating
 */
abstract class Table
{
    //Database object
    protected $db;
    
    public function __construct()
    {
        //Make injection of database object
        $this->db = new Core\Database\Database();
    }

    /**
     * Collect information for table from database
     */
    protected function getFromDb()
    {
        
    }
    
    /**
     * Declare headers for table
     */
    protected function createHeader()
    {
    }
    
    /**
     * Build table array which contains all the table information
     * for building html from it.
     */
    protected function createBody()
    {
        
    }

    /**
     * Add information about the header to $headers property
     */
    final protected function addHeaderColumn($column, $value)
    {
        $this->headers[$column] = $value;
    }
    
    /**
     * Combine results of all methods and return final table's html
     */
    final public function create()
    {
        //Задаем первую строку таблицы
        $table['row_first']=$table_matrix[0];
        
        //Задаем матрицу таблицы
        unset($table_matrix[0]);
        $table['matrix']=$table_matrix;
        
    }
    
    /**
     * Define columns properties, like type, etc.
     */
    protected  function setColumnsExtraProperties()
    {
    }
}

