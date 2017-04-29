<?php

namespace Core;

use Core\Uri;

/* 
 * Provide methods for organizing routing
 */
class Route
{
    /**
     * Define how params in routes should look
     */
    const ROUTE_PARAMS_PATTERN = "/^\{([a-zA-Z\_]+)\}$/";
    
    /**
     * Keep possible ralations between piece of route and uri
     */
    const ROUTE_URI_RELATIONS = ['equal', 'requirement_sutisfied', 'has_param'];
    
    /**
     * Keep information about all routes resolution to controllers and actions
     */
    public $routes = [];
    
    /**
     * Route, found by client uri
     */
    private $usedRoute = false;
    
    /**
     * Instance of Uri class object
     */
    
    public function __construct()
    {
        $this->uri = $GLOBALS['uri'];
    }
    
    /**
     * Add route to routes array
    */
    public function add($path_input, $controller_action_str, $requirements = null)
    {
        //Get controller and action name
        $controller_action = explode(":", trim($controller_action_str));
        
        //Remove backspaces at the edges if some present
        $path = trim($path_input);
        
        //Push retrieved data to routes array
        $this->routes[$path]['controller'] = $controller_action[0];
        $this->routes[$path]['action'] = $controller_action[1];
        
        //Push requirements to routes array if some presented
        if ($requirements !== null) {
            $this->routes[$path]['requirements'] = $requirements;
        }
        
        return $this;
    }
    
    /**
     * Get all routes
     */
    public function getAll()
    {
        return $this->routes;
    }
    
    /**
     * Build detailed array containing all routes from routes.php
     */
    public function build()
    {
        foreach ($this->routes as $route_uri => $route_array) {
            $this->routes[$route_uri]['path'] = $this->uri->parse($route_uri)->getPath();
        }
    }
    
    /**
     * Uniquily find route by uri from client
     */
    public function findRouteByClientUri()
    {
        //Transform to array uri string from client 
        $uri = $this->uri->parse()->getPath();

        //Start working with all existing routes
        $routes = $this->routes;
        
        //Base route was found in uri and routes arrays
        if (count($uri) == 0 && isset($routes['/'])) {
            return ['/' => $routes['/']];
        }
        
        //Base route was found in uri, but there is no such route in routes array
        if (count($uri) == 0 && !isset($routes['/'])) {
            return false;
        }
        
        //Let's remove routes which number of pieces different with uri
        $sized_routes = $this->removeRoutesUnsizedWithUri($routes, $uri);
        
        //There is no routes after removing
        if ($sized_routes === false) {
            return false;
        }
        
        //Iterate over pieces in path of uri from client
        foreach(array_keys($uri) as $uri_piece_key) {
            //Filter routes, leave only that have relation with uri piece
            //on current loop step
            $routes_final = $this->filterRoutesByUriPiece($sized_routes, $uri, $uri_piece_key);
        }
        
        //Remember route found and to 'usedRoutes' property and return
        if (count($routes_final) > 0) {
            $this->usedRoute = $routes_final;
            return $routes_final;
        }
        
        return false;
    }
    
    /**
     * Get route param name without braces
     * 
     * @return  Return param name cleared from braces, and false if param
     * doesn't matches regular expression
     */
    public function getParam($param_in_braces)
    {   
        //Check if param matches regular expression
        if (preg_match(self::ROUTE_PARAMS_PATTERN, $param_in_braces, $matches)) {
            return $matches[1];
        }
        
        return false;
    }
    
    /**
     * Get all route's parameters values
     */
    private function getRouteParamsValues($route_str)
    {   
        //Retrieve route array, path
        $path = $this->routes[$route_str]['path'];
        
        //All the route's parameters
        $params_values = [];
        
        //Transform to array uri string from client 
        $uri = $this->uri->parse()->getPath();
        
        //Look for parameters
        foreach ($path as $param_key => $param_in_braces) {
            //Get param name without braces
            $param = $this->getParam($param_in_braces);
            
            if ($param !== false) {
                $params_values[] = $uri[$param_key];
            }
        }
        
        //Return found parameters
        if (count($params_values) > 0) {
            return $params_values;
        }

        return false;
    }
    
    /**
     * Get route parameter requirement
     */
    public function getParamRequirement($route_str, $param_name)
    {
        if (isset($this->routes[$route_str]['requirements'][$param_name])) {
            return $this->routes[$route_str]['requirements'][$param_name];
        }
         
        return false;
    }
    /**
     * Check if parameter requirement executes
     */
    public function checkParamRequirement($route_str, $param_name, $uri_piece)
    {
        $requirement = $this->getParamRequirement($route_str, $param_name);
        
        if ($requirement === false) {
            \error(
                "Requirement for parameter ".$param_name." in route ".
                $route_str." doesn't exist"
            );
        }
        
        $check_result = preg_match('/^'.$requirement.'$/', $uri_piece);
        
        if ($check_result === false) {
            error("preg_match error");
        }
        
        return (bool) $check_result;
    }
    
    /**
     * Find routes which meet given uri piece.
     * 
     * @param array $routes Routes which we still have after comparison 
     *    of previous piece.
     * @param integer $uri_piece_key Specifies key of piece in given uri
     *    starting from zero.
     * @param string  $uri Uri which piece we use to compare.
     * 
     * @return mixed Return array with all found routes
     *    or false if nothing was found.
     */
    public function filterRoutesByUriPiece($routes, $uri, $uri_piece_key)
    {
        //Keep all found routes grouped by certain relation
        $relation_routes = [];
                
        //Get piece from uri by key
        $uri_piece = $uri[$uri_piece_key];
        
        //Loop over routes which we still have after comparison of previous
        //piece and find any, which has one of relation declared in
        //$this->ROUTE_URI_RELATIONS
        foreach ($routes as $route_str => $route) {
            //Extract piece from route with same key as given uri piece has
            $route_piece = $route['path'][$uri_piece_key];
            
            //Get relation between piece of route and uri
            $relation = $this->getRouteUriPieceRelation($route_piece, $uri_piece, $route_str);
            
            if ($relation !== false) {
                $relation_routes[$relation][$route_str] = $route;
            }
        }
        
        //Find relation, which has highest priority
        $winner_relation = $this->findHighestPriorityRelation($relation_routes);
        
        if ($winner_relation !== false) {
            return $relation_routes[$winner_relation];
        }
        
        return false;
    }
    
    /**
     * Get what the relation stand between piece of route and piece of uri.
     * 
     * @param string $route_piece Piece of route path.
     * @param string $uri_piece Piece of uri path.
     * @param string $route_str Route which piece we have.
     * 
     * @return mixed Return string with name of relation between given route piece
     *    and uri piece. And false if there is no relation was found.
     */
    public function getRouteUriPieceRelation($route_piece, $uri_piece, $route_str) {
        //Extract route param name without braces if this piece is param
        $param_name = $this->getParam($route_piece);
        
        //Variant 1: path piece is not a parameter,
        //so we just compare pieces are equal or not
        if ($route_piece === $uri_piece) {
            return 'equal';
        }

        //Variant 2: path piece is a parameter and parameter has requirements
        if ($param_name !== false &&
            $this->routeHasRequirements($route_str) &&
            $this->checkParamRequirement($route_str, $param_name, $uri_piece)
        ) {
            return 'requirement_sutisfied';
        }

        //Variant 3: path piece is a parameter and parameter doesn't have requirements
        if ($param_name !== false &&
            !$this->routeHasRequirements($route_str)
        ) {
           return 'has_param';
        }
        
        return false;
    }
    
    /**
     * Remove routes which have number of pieces defferent from uri.
     * 
     * @param array $routes Full routes arrays.
     * @param array $uri Pieces of uri path, i.e. first part before '?'.
     * 
     * @return array Routes purified from unsized with uri.
     */
    public function removeRoutesUnsizedWithUri($routes, $uri)
    {
        foreach ($routes as $route_str => $route) {
            if (count($route['path']) != count($uri)) {
                unset($routes[$route_str]);
            }
        }
        
        if (count($routes) === 0) {
            return false;
        }
        
        return $routes;
    }
    
    /**
     * Find relation which has highest priority in routes grouped by relation.
     * 
     * @param array $relation_routes Routes grouped by relation.
     * 
     * @return mixed String with name of relation.
     *    False if no relation was found.
     */
    private static function findHighestPriorityRelation($relation_routes)
    {
        //Loop over relations from high priority to low priority.
        //If number of routes for a relation is positive,
        //then stop looping and recognize this relation as a winner.
        foreach (self::ROUTE_URI_RELATIONS as $relation) {
            if (isset($relation_routes[$relation])) {
                $relation_number = count($relation_routes[$relation]);
            } else {
                $relation_number = 0;
            }
            
            if ($relation_number > 0) {
                return $relation;
            }
        }
        
        //There is no relation was found
        return false;
    }
    
    /**
     * Check if route has requirements
     */
    public function routeHasRequirements($route_str) {
        return isset($this->routes[$route_str]['requirements']);
    }
    
    /**
     * Starts controller action
     * 
     * @return string Html flow as a result of controller execution
     */
    public function startController()
    {
        //Get controller name and call this controller
        $route_array = $this->findRouteByClientUri();

        if ($route_array === false) {
            //Error 404
            echo "<h1>Error 404, page not found.</h1>";
            exit;
        } else {
            $route = reset($route_array);
            $route_str = key($route_array);
        }
        
        //Take controller and action name
        $controller_name = $route['controller'].'Controller';
        $action_name = $route['action'].'Action';

        //Check for controller file existence
        if (!file_exists(CONTROLLERS_PATH.$controller_name.".php")) {
            \error("Cannot find controller class file ".CONTROLLERS_PATH.$controller_name.".php");
        }
        //
        //Attach controller file
        require_once CONTROLLERS_PATH.$controller_name.".php";

        //Handle error of unexistent controller name
        if (!class_exists($controller_name)) {
            \error("Controller class with name ".$controller_name." doesn't exist.");
        }

        //Execute controller
        $controller = new $controller_name;

        if (!method_exists($controller, $action_name)) {
            \error(
                "Method ".$action_name." of controller class ".
                $controller_name." doesn't exist."
            );
        }

        //Give this values to 
        $params_values = $this->getRouteParamsValues($route_str);
        
        if ($params_values !== false) {
            $params_values_transfer = array_values($params_values);
        } else {
            $params_values_transfer = [];
        }
        
        //Run controller
        return $controller->$action_name(...$params_values_transfer);
    }
    
    /**
     * Start test if necessary
     */
    public function startTest()
    {
        if ($this->uri->parse()->getPath(0) === 'tests' &&
            $this->uri->parse()->getPath(1) &&
            $this->uri->parse()->getPath(2)
        ) {
            require_once '../tests/'.$this->uri->parse()->getPath(1).'/'.$this->uri->parse()->getPath(2).'.php';
            exit;
        }

        if ($this->uri->parse()->getPath(0) === 'tests' &&
            $this->uri->parse()->getPath(1) &&
            !$this->uri->parse()->getPath(2)
        ) {

            require_once ROOT_PATH . 'web/tests/'.$this->uri->parse()->getPath(1).'.php';
            exit;
        }        
    }
}