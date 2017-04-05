<?php

namespace Core;

/**
 * Realize methods which should be overwritten by class inherited of Table class
 */
trait TableDefinition
{
    /**
     * Retrieve data for table from database only
     * and save it to $data property. Futher processing
     * of this data we perform in prepareData() method.
     */
    protected function getData()
    {
        trigger_error(
            'Method ' . __METHOD__ . 'should be overriden in inherited class.',
            E_USER_ERROR
        );
    }
    
    /**
     * Build table array which contains all the table information
     * for building html from it. Should be overriden by inherited class.
     */
    protected function prepareData()
    {
        trigger_error(
            'Method ' . __METHOD__ . 'should be overriden in inherited class.',
            E_USER_ERROR
        );
    }
    
    /**
     * Declare headers for table
     */
    protected function setHeader()
    {
        trigger_error(
            'Method ' . __METHOD__ . 'should be overriden in inherited class.',
            E_USER_ERROR
        );
    }
    
    /**
     * Set columns properties here, like type, etc.
     */
    protected  function setColumns()
    {
        trigger_error(
            'Method ' . __METHOD__ . 'should be overriden in inherited class.',
            E_USER_ERROR
        );
    }

    /**
     * Set general properties, such as border width and single row height
     */
    protected  function setOtherProperties()
    {
        trigger_error(
            'Method ' . __METHOD__ . 'should be overriden in inherited class.',
            E_USER_ERROR
        );
    }    
}