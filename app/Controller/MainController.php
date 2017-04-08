<?php

use Core\Controller;
use App\Table\PatentTable;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->listAction('russia', 'patents');
    }
    
    public function listAction($country, $object)
    {
        //Save table, should be called before output table
        $this->save($country, $object);
        
        //Build table
        $table = new PatentTable();
        $table->setCountry($country);
        
        //Return table html
        return $table->build();
    }
    
    public function deleteAction($country, $object, $id)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }

        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($object);
        echo('hello' . $id. $country . $object);
                
        //Query database
        $this->db->prepare("
            DELETE FROM
                `" . $object . "s`
            WHERE 
                id=?
        ")
            ->bindParam('d', $id)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $object . '/');
    }
    
    public function addAction($country, $object)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }
        
        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($object);
        
        //Query database
        $this->db->prepare("
            INSERT INTO
                `" . $object . "s`
            SET
                `country_name`=?
        ")
            ->bindParam('s', $country)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $object . '/');
    }
    
    /**
     * Save table data
     */
    private function save($country, $object)
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
        $this->stopOnIncorrectObject($object);
        
        //Loop over form rows and save them
        foreach( $_POST['Form'] as $row => $columns ){
            $this->db->prepare("
                UPDATE
                    `" . $object . "s`
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
    private function stopOnIncorrectObject($object)
    {
        if ( $object !== 'patent' && $object !== 'trademark' ) {
            error("Error");
            return;
        }
    }
}
