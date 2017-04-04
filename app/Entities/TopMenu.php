<?php

namespace Entities;

use Core\Database\Database;

class TopMenu
{
    protected $country;
    /**
     * Store database object
     */
    public $db;
    
    public function __construct($country)
    {
        $this->db = new Database();
    }
    
    /**
     * Get list of top menu items
     * 
     * @return array Top menu items
     */
    public function getList()
    {
        //Retrieve all top menu items
        $result = $this->db->prepare("
            SELECT
                *
            FROM
                `countries`
            ORDER BY
                `order` ASC
        ")
            ->exec()
            ->getResult();
        
        //Put got items to array
        $items = $result->fetchAll();
        
        //In case of no items
        if ( count($items) == 0 ) {
            return [];
        }
        
        //Loop over menu items
        foreach( $items as $id => $item ) {
            $items[$id]['class'] = get_class_depend_on_uri( "=", $this->country, $item['name'] );
            $items[$id]['href'] = '/'.$item['name'].'/patents/';
        }

        return $items;
    }
}
