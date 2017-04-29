<?php

namespace App\Entity;

use Core\Database;

class TopMenu
{
    //Store current opened country and property
    protected $country;
    protected $possession;
    
    /**
     * Store database object
     */
    public $db;
    
    public function __construct($country, $property)
    {
        $this->db = new Database();
        
        //Get current opened country and property
        $this->country = $country;
        $this->possession = $property;
    }
    
    /**
     * Get list of top menu items
     * 
     * @return array Top menu items
     */
    public function getCountryList()
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
            $items[$id]['class'] = $this->getHrefClass( $this->country, "=", $item['name'] );
            $items[$id]['href'] = '/' . $item['name'] . '/' . $this->possession . 's/';
        }

        return $items;
    }

    /**
     * Get list of top menu items
     * 
     * @return array Top menu items
     */
    public function getPossesionList()
    {
        //Retrieve all top menu items
        $result = $this->db->prepare("
            SELECT
                *
            FROM
                `possessions`
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
            $items[$id]['class'] = $this->getHrefClass( $this->possession.'s', "=", $item['name'] );
            $items[$id]['href'] = '/' . $this->country . '/' . $item['name'] . '/';
        }

        return $items;
    }
    
    /**
     * Return class for country hyperlink
     */
    protected function getHrefClass($compared, $rule, $value) {
        switch($rule) {
            case "=":
                if( trim($compared) == $value ){
                    return "not-lighted";
                }else{
                    return "lighted";
                }
            break;
            case "!=":
                if( trim($compared) != $value ){
                    return "not-lighted";
                }else{
                    return "";
                }
            break;
        }
    }    
}
