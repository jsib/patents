<?php

namespace App\Table;

use Core\Table;
use Core\Date;
use App\Entity\Country;

class PropertyTable extends Table
{
    /**
     * @var string $country Table's country name
     * @var string $country_rus Table's country russian name
     * @var string $property Table's property name
     */
    public $country;
    public $country_rus;
    public $property;
    
    protected function getData()
    {
        $property_plural = $this->property . 's';
        
        //Query database and get result
        $result = $this->db->prepare("
            SELECT
                `$property_plural`.`id` as `id`,
                `$property_plural`.`name` as `name`,
                `$property_plural`.`certificate` as `certificate`,
                `$property_plural`.`request` as `request`,
                `$property_plural`.`priority` as `priority`,
                `$property_plural`.`registration` as `registration`,
                `$property_plural`.`paid_before` as `paid_before`,
                `$property_plural`.`expire` as `expire`,
                `$property_plural`.`comment` as `comment`
            FROM
                `$property_plural`
            WHERE
                country_name=?
        ")
            ->bindParam( 's', $this->country )
            ->exec()
            ->getResult();
        
        //Push array to $body property
        $this->data = $result->fetchAll();
    }
    
    protected function prepareData()
    {
        $matrix = $this->data;
                
        if ( count($matrix) == 0 ) {
            $this->matrix = [];
            return;
        }
        
        foreach ($matrix as $id => $patent) {
            //Add column for delete action links
            if( $this->auth->userHasRight('edit') ){
                $matrix[$id]['delete'] = "Удалить";

                $this->links[$id]['delete']['href'] = '/' . $this->country . '/' . $this->property . '/delete/' . $patent['id'];

                $this->appearance[$id]['delete']['style'] = 'color:red;';
                $this->appearance[$id]['delete']['onclick'] = 
                    "if(!confirm(\"Удалить?\")) return false;";
            } else {
                $this->appearance[$id]['delete']['style'] = '';
                $this->appearance[$id]['delete']['onclick'] = '';
                $this->links[$id]['delete']['href'] = '';
                $this->appearance[$id]['delete']['class'] = '';
            }

            //Clean empty dates
            foreach ($patent as $property => $value) {
                $this->appearance[$id][$property]['class'] = '';
                    
                if (!isset( $this->columns[$property]['type'] )) {
                    continue;
                }
                
                if ($this->columns[$property]['type'] == 'date') {
                    $matrix[$id][$property] = Date::human($value);
                    $this->appearance[$id][$property]['class'] = 'datepickerTimeField';
                }
            }
        }
        
        $this->matrix = $matrix;
    }    
    
    protected function setHeader()
    {
        //Columns, available for all users
        $this->addHeaderColumn('id', 'Id патента');
        $this->addHeaderColumn('name', 'Имя товарного знака');
        $this->addHeaderColumn('comment', 'Примечание');
        $this->addHeaderColumn('certificate', '№ свидетельства');
        $this->addHeaderColumn('request', '№ заявки');
        $this->addHeaderColumn('priority', 'Приоритет');
        $this->addHeaderColumn('registration', 'Регистрация');
        $this->addHeaderColumn('paid_before', 'Оплачено до');
        $this->addHeaderColumn('expire', 'Срок действия, до');
        
        //Column for 'Delete' action available depends on user rights
        if ( $this->auth->userHasRight('edit') ) {
            $this->addHeaderColumn('delete', '');
        }
    }
    
    protected function setColumns() {
        //Set columns widths! Keep order here!
        $this->setColumnWidth('name', '260px');
        $this->setColumnWidth('comment', '260px');
        $this->setColumnWidth('certificate', '150px');
        $this->setColumnWidth('request', '150px');
        $this->setColumnWidth('priority', '150px');
        $this->setColumnWidth('registration', '150px');
        $this->setColumnWidth('expire', '150px');
        $this->setColumnWidth('paid_before', '150px');
        //Column for 'Delete' action available depends on user rights
        if ( $this->auth->userHasRight('edit') ) {
            $this->setColumnWidth('delete', '70px');
        }

        //Set columns type
        $this->setColumnType('id', 'hidden');
        $this->setColumnType('priority', 'date');
        $this->setColumnType('registration', 'date');
        $this->setColumnType('paid_before', 'date');
        $this->setColumnType('expire', 'date');
        
        //Set columns input fields width
        $this->setColumnInputWidth('name', '260px');
        $this->setColumnInputWidth('comment', '260px');
        $this->setColumnInputWidth('certificate', '150px');
        $this->setColumnInputWidth('request', '150px');
        $this->setColumnInputWidth('priority', '150px');
        $this->setColumnInputWidth('registration', '150px');
        $this->setColumnInputWidth('expire', '150px');
    }
    
    protected function setOtherProperties()
    {
        $this->setRowHeight(20);
        $this->setBorderWidth(1);
        $this->setDefaultSortColumn('id');
        $this->setDefaultSortDirection('asc');
        
        //Set country name
        $this->country_rus = (new Country())->getCountry($this->country)['name_rus'];
        
        //Set form action
        $this->formAction = '/' . $this->country . '/' . $this->property . 's/';
    }
    
    public function setCountry($country)
    {
        $this->country = $country;
    }
    
    public function setProperty($property)
    {
        $this->property = $property;
    }
}


