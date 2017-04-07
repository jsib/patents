<?php

use Core\Controller;
use Core\Database;
use Core\View;
use Core\Route;

class SignInController extends Controller
{
    public function formAction()
    {
        return $this->view->load('sign_in');
    }
    
    public function checkAction()
    {
        //Check all input data to be presented
        if (!isset($_POST['name']) || !isset($_POST['password'])) {
            return $this->sendJsonAnswer('error');
        }
        
        //Get user's and email from ajax request
        $name = $_POST['name'];
        $password = $_POST['password'];
        
        //Check password correctness
        if ($this->checkUserHash($name, $password) === false) {
            return $this->sendJsonAnswer('error');
        }
        
        //Save user name to cookie
        $_SESSION['user']['name'] = $name;
        
        //Send answer to client
        return $this->sendJsonAnswer('success');
    }
    
    /**
     * Perform sign out
     */
    public function signOutAction()
    {
        unset($_SESSION['user']['name']);
        header('Location: '.$this->auth->signInRoute);
    }
    
    /**
     * Check user hash
     */
    private function checkUserHash($name, $password)
    {
        //Get user's password hash from by email
        $result = $this->db->prepare("SELECT `password_hash` FROM `users` WHERE `name`=?")
            ->bindParam('s', $name)
            ->exec()
            ->getResult();
        
        //User with this email not exist
        if ($result->numRows() == 0){
            return false;
        }
        
        //Generate hash
        if( $this->generateHash($name, $password) == $result->fetchColumn('password_hash') ) {        
            return true;
        }
        
        return false;
    }
    
    /**
     * Generate hash based on username and password
     */
    private function generateHash($user, $password)
    {
        $hash = sha1( md5($user) . "+++" . $password );
        return $hash;
    }    
    
}
