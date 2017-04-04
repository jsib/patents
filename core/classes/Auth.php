<?php

namespace Core\Auth;

use Core\Facades\Uri;
use Core\Facades\Route;
use Core\Facades\DB;

/* 
 * Provide authentication methods
 */
class Auth
{
    /**
     * Routes, which can be visited anonymously, e.g. without sign in
     */
    private $anonymousRoutes = [];
    
    /**
     * Route leads to sign in form, should be anonymous always
     */
    private $signInRoute = '';
    
    /**
     * Route leads to sign in form check, should be anonymous always
     */
    private $signInCheckRoute ='';
    
    /*
     * Start cookie session and define is user authenticated
     */
    public function init()
    {
        //Start cookie session
        session_start();
        
        if ($this->signInRoute == '') {
            \error("Sing in route is not defined. Define it in auth.php");
        }

        if ($this->signInCheckRoute == '') {
            \error("Sing in check route is not defined. Define it in auth.php");
        }
        
        if (!$this->hasSignIn() && !$this->isRouteAnonymous()) {
            header('Location: '.$this->signInRoute);
        }
    }
    
    /**
     * Check if user signed in
     */
    public function hasSignIn()
    {
        return isset($_SESSION['user']['name']);
    }
    
    /**
     * Add anonymous route
     */
    public function addAnonymousRoute($route_str)
    {
        $this->anonymousRoutes[$route_str] = $route_str;
    }
    
    /**
     * Define is route anonymous
     * 
     * @return boolean
     */
    public function isRouteAnonymous()
    {
        //Get route by client uri
        $route_str = array_keys(Route::findRouteByClientUri())[0];
        
        //Is it in anonymous routes array?
        return isset($this->anonymousRoutes[$route_str]);
    }
    
    /**
     * Define sign in route.
     */
    public function setSignInRoute($route_str)
    {
        //Sign in route should be anonymous always
        $this->addAnonymousRoute($route_str);
        
        //Define class property
        $this->signInRoute = $route_str; 
    }
    
    /**
     * Define sign in form check action route
     */
    public function setSignInCheckRoute($route_str)
    {
        //Sign in check route should be anonymous always
        $this->addAnonymousRoute($route_str);
        
        //Define class property
        $this->signInCheckRoute = $route_str;
    }
    
    /**
     * Get user id which is signed in now
     */
    public function getSignedInUserId()
    {
        $user_name = $_SESSION['user']['name'];
        
        $result = DB::prepare("SELECT `id` FROM `users` WHERE `name`=?")
            ->bindParam('s', $user_name)
            ->exec()
            ->getResult();
        
        if($result->numRows() == 0) {
            return false;
        }
        
        return $result->fetchColumn('id');
    }
    
    /**
     * Check if user has right to perform action
     */
    public function getRight($action)
    {
        
    }
    
}