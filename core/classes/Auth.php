<?php

namespace Core;

use Core\Database;

/* 
 * Provide authentication methods
 */
class Auth
{
    /**
     * @var array $anonymousRoutes Define routes, which can be visited anonymously
     * @var string $signInCheckRoute Leads to sign in form check, it's always anonymous
     * @var object $db Acess to database methods
     * @var object $route Access to route object
     */
    private $anonymousRoutes, $signInCheckRoute, $db, $route;
    
    /**
     * @var string $signInRoute Leads to sign in form, this route is always anonymous
     */
    public $signInRoute;
    
    public function __construct($route)
    {
        $this->db = new Database();
        $this->route = $route;
    }
    
    /*
     * Start cookie session and define is user authenticated
     */
    public function init()
    {
        //Start cookie session
        session_start();
        
        //Set session timelife
        session_set_cookie_params(10800);
        
        if ($this->signInRoute == '') {
            \error("Sign in route is not defined. Define it in auth.php");
        }

        if ($this->signInCheckRoute == '') {
            \error("Sign in check route is not defined. Define it in auth.php");
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
     * Get current signed in user name
     * 
     * @return mixed Signed in user name
     */
    public function getSignedInUserName()
    {
        if ( $this->hasSignIn() ) {
            return $_SESSION['user']['name'];
        } else {
            return 'anonymous';
        }
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
        $route = $this->route->findRouteByClientUri();
                
        //Check if route found
        if ($route === false) {
            error("Route for current client uri is not found. Check route.php.");
        }
        
        $route_str = array_keys($route)[0];
        
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
        
        $result = $this->db->prepare("SELECT `id` FROM `users` WHERE `name`=?")
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
    public function userHasRight($right)
    {
        //Get session user name
        $user_name = $this->getSignedInUserName();
        
        //Query database
	$result = $this->db->prepare("
            SELECT
                `right` 
            FROM 
                `users`
            WHERE 
                `name`=?
        ")
            ->bindParam('s', $user_name)
            ->exec()
            ->getResult();
        
        //No entry for this user in database, possibly it's anonymous user 
        if ($result->numRows() == 0) {
            return false;
        }
        
        //User doesn't have needed rights
        if ($result->fetch()['right'] != $right) {
            return false;
        }
        
        return true;
    }
}