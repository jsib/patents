<?php

//Database host
const DB_HOST = 'localhost';

//Database user
const DB_USER = 'ilya';

//Database password
const DB_PASSWORD = 'local';

//Database name
const DB_DATABASE = 'patents';

//Main project dir path
const ROOT_PATH = '/home/patents.acoustic-group.net/www/';

//Path to core dir
define(CORE_PATH,  ROOT_PATH . 'core/');

//Path to core classes for autoloader
define(CLASSES_PATH, ROOT_PATH . 'core/classes/');

//Path to root application dir
define(APP_PATH, ROOT_PATH . 'app/');

//Path to controllers
define(CONTROLLERS_PATH, APP_PATH . 'Controller/');

//Encoding of .php, .html, etc source files
define(FILES_ENCODING, 'UTF-8');

//Path to dir with assets (like css and js files, etc)
define(ASSETS_PATH, '/assets/');

//Define path to views files
define(VIEWS_PATH, ROOT_PATH . 'resources/views/');


