<?php

use Core\Route;
use Core\Auth;
use Core\Uri;

//Include main config file
require_once('../app/config.php');

//Require shortcuts for classes functions
require_once(CORE_PATH . "shortcuts.php");

//Require debug before autoloader to handle autoloader errors correctly
require_once(CLASSES_PATH . "Debug.php");

//Set error handler
set_error_handler(['Debug', 'handleErrors']);

//Require autoloader class
require_once(CLASSES_PATH . "Autoloader.php");

//Set classes autoloader
spl_autoload_register('Autoloader::load');

//Create instance of Uri object
$uri = new Uri();

//Create instance of route object
$route = new Route();

//Include routes, must be after autoloader
require_once(APP_PATH . 'routes.php');

//Create instance of auth object
$auth = new Auth($route);

//Include auth rules including anonymous routes, etc
require_once(APP_PATH . 'auth.php');

//Build routes table
$route->build();

//Start session, etc
$auth->init();

//Start controller
echo $route->startController();
?>
