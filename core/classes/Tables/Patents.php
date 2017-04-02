<?php

class PatentsTable extends Table
{
    private function getFromDb()
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
            ->bind_param( 's', (int) Request::get('country') )
            ->getResult();
        
        //Push array to $body property
        $this->body = $result->fetchAll();
    }
    
    private function createHeader()
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
    
    private function setColumnsExtraProperties() {
        setColumnType('id', 'hidden');
        setColumnType('priority', 'date');
        setColumnType('paid_before', 'date');
        setColumnType('expire', 'date');
    }
    
    private function createBody()
    {
        $table = $this->body;
                
        if ( count($table) >0 ) {
            $new_body = [];
            
            foreach ($table as $id => $patent) {
		if( $this->auth->getRight('delete') ){
                    $table[$id]['delete'] = "Удалить";
                    
                    $this->links[$id]['delete']['href'] =
                        uri_make([
                            'action' => 'delete_patent',
                            'id' => $id
                        ]);
                    
                    $this->appearance[$id]['delete']['style'] = "color:red;";
                    $this->appearance[$id]['delete']['onclick'] = 
                        "if(!confirm(\"Удалить?\")) return false;";
		}
            }
        }
        
        $this->table = $table;
    }
}


