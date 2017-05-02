<?php

use Core\Route;
use Core\Uri;
use Core\Database;
use Core\Email;
use Core\Notify;
use Core\Auth;
use App\Entity\Country;
use App\Table\PossessionEmailTable;

//Set default timezone
date_default_timezone_set('Europe/Moscow');

//Include main config file
require_once('/home/patents.acoustic-group.net/www/app/config.php');

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

//Route, uri, auth just for compatibility with web class Table for web
$route = false;
$uri = new Uri();
$auth = new Auth($route);

//Connect to database
$db = new Database();

$notify = new Notify();

$notify->run();
