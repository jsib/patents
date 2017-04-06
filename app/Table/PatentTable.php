<?php

namespace App\Table;

use Core\Table;
use Core\Date;
use App\Entity\Country;

class PatentTable extends Table
{   
    /**
     * Table's country name
     */
    public $country;
    
    /**
     * Table's country russian name
     */
    public $country_rus;
    
    protected function getData()
    {
        //Query database and get result
        $result = $this->db->prepare("
            SELECT
                `patents`.`id` as `id`,
                `patents`.`name` as `name`,
                `patents`.`certificate` as `certificate`,
                `patents`.`request` as `request`,
                `patents`.`priority` as `priority`,
                `patents`.`registration` as `registration`,
                `patents`.`paid_before` as `paid_before`,
                `patents`.`expire` as `expire`,
                `patents`.`comment` as `comment`
            FROM
                `patents`
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
            if( $this->auth->getRight('delete') ){
                $matrix[$id]['delete'] = "Удалить";

                $this->links[$id]['delete']['href'] =
                    uri_make( ['action' => 'delete_patent', 'id' => $id] );

                $this->appearance[$id]['delete']['style'] = 'color:red;';
                $this->appearance[$id]['delete']['onclick'] = 
                    "if(!confirm(\"Удалить?\")) return false;";
            } else {
                $this->appearance[$id]['delete']['style'] = '';
                $this->appearance[$id]['delete']['onclick'] = '';
            }

            //Clean empty dates
            foreach ($patent as $property => $value) {
                if (!isset( $this->columns[$property]['type'] )) {
                    continue;
                }
                
                if ($this->columns[$property]['type'] == 'date') {
                    $matrix[$id][$property] = Date::human($value);
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
        if ( $this->auth->getRight('delete') ) {
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
        $this->setColumnWidth('delete', '70px');

        //Set columns type
        $this->setColumnType('id', 'hidden');
        $this->setColumnType('priority', 'date');
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
        $this->setRowHeight(28);
        $this->setBorderWidth(1);
        $this->setDefaultSortColumn('id');
        $this->setDefaultSortDirection('asc');
        
        $this->country_rus = (new Country())->getCountry($this->country)['name_rus'];
    }
}


