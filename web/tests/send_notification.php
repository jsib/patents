<?php

use Core\Route;
use Core\Auth;
use Core\Uri;
use Core\Database;
use Core\Email;
use Core\Notify;
use App\Entity\Country;
use App\Table\PossessionEmailTable;

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

//Connect to database
$db = new Database();

$table = new PossessionEmailTable();

//Return table html
return $table->build();

?>
