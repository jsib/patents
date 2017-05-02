<?php

namespace App\Table;

use Core\Table;
use Core\Date;
use App\Entity\Country;

class PossessionEmailTable extends Table
{
    /**
     * @var string $country Table's country name
     * @var string $country_rus Table's country russian name
     * @var string $property Table's property name
     */
    public $country;
    public $country_rus;
    public $property;

    /** Timestamp for not defined dates */
    private $notDefinedTimestamp = 10000000000;


    /**
     * Get possessions list from database
     */    
    protected function getData()
    {
        //Retrieve matrix from database
        $result = $this->db->prepare("
            (SELECT * FROM `patents`)
            UNION
            (SELECT * FROM `trademarks`)
        ")
            ->exec()
            ->getResult();
        
        //Push array to $data property
        $this->data = $result->fetchAll();
    }
    
    /**
     * Prepare data which we got from database
     */
    protected function prepareData()
    {
        $possessions_ready = $this->data;
                
        //No possessions in database
        if (count($possessions_ready) == 0) {
            return false;
        }
        
        //Loop over list of possessions
        foreach( $possessions_ready as $key => $possession ) {
            
            //Format name
            $possessions_ready[$key]['name'] = mb_strcut($possession['name'], 0, 5000, "UTF-8");
            
            //Get countries list
            $entity = new Country();
            $countries = $entity->getList();
            
            //Country name
            $possessions_ready[$key]['country'] = $countries[$possession['country_name']]['name_rus'];
            
            //Appearance
//            $this->appearance[$key]['expire']['style'] = 'color:' . $this->getColor($possession['expire']) . ';';
            
            //Format dates
            foreach ($possession as $property => $value) {
                $this->appearance[$key][$property]['class'] = '';
                    
                if (!isset( $this->columns[$property]['type'] )) {
                    continue;
                }
                
                if ($this->columns[$property]['type'] == 'date') {
                    $possessions_ready[$key][$property] = Date::human($value, 'не задано');
                }
            }
        }

        //Labels for dates
        $labels = [];

        //Sort data array and create labels array
        $this->sortAndLabelDataByExpirePaid($possessions_ready, $labels);

        foreach ($possessions_ready as $key => $value) {
            foreach (['expire', 'paid_before'] as $column) {
                if (isset($labels[$key][$column])) {
                    $this->appearance[$key][$column]['style'] = 'color:' . $labels[$key][$column] . ';';
                }
            }
        }
        
        $this->matrix = $possessions_ready;
    }
    
    protected function setHeader()
    {
        //Columns, available for all users
        $this->addHeaderColumn('name', 'Наименование');
        $this->addHeaderColumn('certificate', '№ свидетельства');
        $this->addHeaderColumn('country', 'Страна');
        $this->addHeaderColumn('paid_before', 'Оплачено, до');
        $this->addHeaderColumn('expire', 'Срок действия, до');
    }
    
    protected function setColumns() {
        //Set columns widths! Keep order here!
        $this->setColumnWidth('name', '400px');
        $this->setColumnWidth('certificate', '100px');
        $this->setColumnWidth('country', '100px');
        $this->setColumnWidth('paid_before', '100px');
        $this->setColumnWidth('expire', '100px');

        //Set columns type. Default type is usual text.
        $this->setColumnType('paid_before', 'date');
        $this->setColumnType('expire', 'date');
    }
    
    protected function setOtherProperties()
    {
        $this->setRowHeight(20);
        $this->setBorderWidth(1);
        $this->dontUseSorting();
        
        //Set view file
        $this->setViewFile('tables/notify/table');
    }
    
    
    private function getColor($input_date)
    {
        //Define time period to next possession renewal
        $diff_sec = strtotime($input_date) - strtotime(date("d.m.Y") );
        $diff_day = round( $diff_sec / (60 * 60 * 24) );

        //Define importance of renewal for each possession
        if( $diff_day < 63 && $diff_day >= 31 ) {
            return 'orange';
        } elseif($diff_day < 31) {
            return 'red';
        } else {
            return 'green';
        }
    }

    /**
* Sort data by date of expiration and create labels for this dates
     *
     * @param $data
     * @param $labels
     */
    protected function sortAndLabelDataByExpirePaid(&$data, &$labels)
    {
        //Keep sorting order here
        $sorted = [];

        foreach ($data as $key => $possession) {
            $expire = strtotime($possession['expire']);
            $paid = strtotime($possession['paid_before']);
            
            if ($expire == '') {
                $expire = $this->notDefinedTimestamp;
            }
            
            if ($paid == '') {
                $paid = $this->notDefinedTimestamp;
            }

            //Take later date
            if ($expire <= $paid) {
                $sorted[$key] = $expire;

                //Create label
                $labels[$key]['expire'] = $this->defineExpiredDateStyle($expire);

                //Create extra label
                if ($expire == $paid) {
                    $labels[$key]['paid_before'] = $labels[$key]['expire'];
                }
            } else {
                $sorted[$key] = $paid;

                //Create label
                $labels[$key]['paid_before'] =  $this->defineExpiredDateStyle($paid);
            }
        }
        
        //Sort by value
        asort($sorted);
        
        //Prepare result array
        foreach($sorted as $key => $empty) {
            $ready_data[$key] = $data[$key];
        }
        
        //Replace given array
        $data = $ready_data;
    }

    /**
     * Define if date is expired
     *
     * @param $timestamp
     * @return bool
     */
    private function defineExpiredDateStyle($timestamp)
    {
        //Behavior for not defined dates
        if ($timestamp == $this->notDefinedTimestamp) {
            return 'black';
        }

        $age_sec = $timestamp - strtotime(date("d-m-Y"));

        $age = ($age_sec - ($age_sec % (3600 * 24))) / (3600 * 24);
        //dump($age_sec, date('d.m.Y', $timestamp));

        //'Red' age
        if ($age < 30) {
            return 'red';
        }

        //'Orange' age
        if ($age < 90) {
            return 'orange';
        }

        //In other cases
        return 'black';
    }
}


