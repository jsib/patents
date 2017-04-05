<?php

namespace App\Entity;

use Core\Database;

class TopMenu
{
    //Store current opened country
    protected $country;
    
    /**
     * Store database object
     */
    public $db;
    
    public function __construct($country)
    {
        $this->db = new Database();
        
        //Get current opened country
        $this->country = $country;
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
            //Set class and hyperlink for item
            $items[$id]['class'] = $this->getHrefClass( "=", $item['name'] );
            $items[$id]['href'] = '/'.$item['name'].'/patents/';
        }

        return $items;
    }
    
    /**
     * Return class for country hyperlink
     */
    protected function getHrefClass($rule, $value) {
        switch($rule){
            case "=":
                if(trim($this->country) == $value){
                    return "not-lighted";
                }else{
                    return "";
                }
            break;
            case "!=":
                if(trim($this->country) != $value){
                    return "not-lighted";
                }else{
                    return "";
                }
            break;
        }
    }    
}
