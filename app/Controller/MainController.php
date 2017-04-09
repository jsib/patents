<?php

use Core\Controller;
use App\Table\PropertyTable;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->listAction('russia', 'patents');
    }
    
    public function listAction($country, $property_plural)
    {
        //Get property single
        $property = substr( $property_plural, 0, strlen($property_plural)-1 );
        
        //Save table, should be called before output table
        $this->save($country, $property);
        
        //Build table
        $table = new PropertyTable();
        
        //Set country and property name
        $table->setCountry($country);
        $table->setProperty($property);
        
        //Return table html
        return $table->build();
    }
    
    public function deleteAction($country, $property, $id)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }

        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($property);
        echo('hello' . $id. $country . $property);
                
        //Query database
        $this->db->prepare("
            DELETE FROM
                `" . $property . "s`
            WHERE 
                id=?
        ")
            ->bindParam('d', $id)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $property . 's/');
    }
    
    public function addAction($country, $property)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }
        
        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($property);
        
        //Query database
        $this->db->prepare("
            INSERT INTO
                `" . $property . "s`
            SET
                `country_name`=?
        ")
            ->bindParam('s', $country)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $property . 's/');
    }
    
    /**
     * Save table data
     */
    private function save($country, $property)
    {
        //Check if cliend wanted to save form
        if ( !isset($_POST['Form']) ) {
            //Do notning
            return;
        }
        
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }
        
        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($property);
        
        //Loop over form rows and save them
        foreach( $_POST['Form'] as $row => $columns ){
            $this->db->prepare("
                UPDATE
                    `" . $property . "s`
                SET
                    `name`=?,
                    `comment`=?,
                    `country_name`=?,
                    `certificate`=?,
                    `request`=?,
                    `priority`=?,
                    `registration`=?,
                    `paid_before`=?,
                    `expire`=?
                WHERE
                    id=?
            ")
                ->bindParam('s', $columns['name'])
                ->bindParam('s', $columns['comment'])
                ->bindParam('s', $country)
                ->bindParam('s', $columns['certificate'])
                ->bindParam('s', $columns['request'])
                ->bindParam('s', date("Y-m-d", strtotime($columns['priority'])))
                ->bindParam('s', date("Y-m-d", strtotime($columns['registration'])))
                ->bindParam('s', date("Y-m-d", strtotime($columns['paid_before'])))
                ->bindParam('s', date("Y-m-d", strtotime($columns['expire'])))
                ->bindParam('d', $columns['id'])
                ->exec();
        }
    }
    
    /**
     * Stops action if object is incorrect
     */
    private function stopOnIncorrectObject($property)
    {
        if ( $property !== 'patent' && $property !== 'trademark' ) {
            error("Error");
            return;
        }
    }
}
