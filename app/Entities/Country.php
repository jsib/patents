<?php

namespace App\Entities;

use Core\Database\Database;

class Country
{
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
    
    public function getCountry()
    {
        $countries = $this->getList();

        if(isset($countries[@$_GET['country']])){
                $country_name=@$_GET['country'];
        }else{
                $country_name="russia";
        }

        return $country_name;
    }        
}

