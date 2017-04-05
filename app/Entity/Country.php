<?php

namespace App\Entity;

use Core\Database;

class Country
{
    public $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function getList()
    {
        $result = $this->db->prepare("SELECT * FROM `countries`")
            ->exec()
            ->getResult();
        
        while( $country = $result->fetch() ){
            $countries[ $country['name'] ] = $country;
        }

        return $countries;
    }
    
    public function getCountry($name)
    {
        //Query database
        $result = $this->db->prepare("SELECT * FROM `countries` WHERE `name`=?")
            ->bindParam('s', $name)
            ->exec()
            ->getResult();
        
        //No countries with this name
        if ($result->numRows() == 0) {
            return false;
        }
        
        return $result->fetch();
    }        
}

