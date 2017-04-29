<?php

use Core\Controller;
use App\Table\PossessionTable;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->listAction('russia', 'patents');
    }
    
    public function listAction($country, $possession_plural)
    {
        //Get property single
        $possession = substr( $possession_plural, 0, strlen($possession_plural)-1 );
        
        //Save table, should be called before output table
        $this->save($country, $possession);
        
        //Build table
        $table = new PossessionTable();
        
        //Set country and property name
        $table->setCountry($country);
        $table->setPossession($possession);
        
        //Return table html
        return $table->build();
    }
    
    public function deleteAction($country, $possession, $id)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }

        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($possession);
                
        //Query database
        $this->db->prepare("
            DELETE FROM
                `" . $possession . "s`
            WHERE 
                id=?
        ")
            ->bindParam('d', $id)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $possession . 's/');
    }
    
    public function addAction($country, $possession)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }
        
        //Stop action on incorrect client input
        $this->stopOnIncorrectObject($possession);
        
        //Query database
        $this->db->prepare("
            INSERT INTO
                `" . $possession . "s`
            SET
                `country_name`=?
        ")
            ->bindParam('s', $country)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $possession . 's/');
    }
    
    /**
     * Save table data
     */
    private function save($country, $possession)
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
        $this->stopOnIncorrectObject($possession);
        
        //Loop over form rows and save them
        foreach( $_POST['Form'] as $row => $columns ){
            $this->db->prepare("
                UPDATE
                    `" . $possession . "s`
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
    private function stopOnIncorrectObject($possession)
    {
        if ( $possession !== 'patent' && $possession !== 'trademark' ) {
            error("Error");
            return;
        }
    }
}
