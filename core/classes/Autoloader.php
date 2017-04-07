<?php

/* 
 * This class responsible for autoload necessary classes
 */
class Autoloader
{
    /**
     * Autoload necessary class
     * 
     * @param type $path_str
     * @return void
     */
    public static function load($path_str)
    {
        //Split path to pieces
        $path = explode('\\', $path_str);
        
        //Class file in CLASSES_PATH folder
        if( $path[0] == 'Core') {
            $path_ready = CLASSES_PATH . $path[1];
        }
        
        //App file in APP_PATH dir
        if( $path[0] == 'App') {
            $path_app_str = 
            $path_ready = APP_PATH . $path[1]  . '/' . $path[2];
        }
        
        //Autoloader didn't find path
        if (!isset($path_ready) ) {
            error("Autoloader didn't find path for ".$path_str);
        }
        
        require_once $path_ready . '.php';
     }
}

