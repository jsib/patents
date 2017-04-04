<?php

/* 
 * This class responsible for autoload necessary classes
 */
class Autoloader
{
    /**
     * Autoload necessary class
     * 
     * @param type $class
     * @return boolean
     */
    public static function init($class)
    {
        //Split path to pieces
        $parts = explode('\\', $class);
        
        //Entities
        if ( count($parts) == 2 && $parts[0] == 'Entities') {
            require_once ROOT_PATH . 'app/' . $parts[0] . '/' . $parts[1] . '.php';
            return true;
        }
        
        //For classes, which files are in subfolder
        if (isset($parts[1]) && $parts[1] == 'Tables' && isset($parts[2])) {
            require_once CORE_CLASSES_PATH . $parts[1] . '/' . $parts[2] . '.php';
        //Classes files placed directly in main class folder
        } elseif (count($parts) == 1) {
            require_once CORE_CLASSES_PATH . $parts[0] . '.php';
        } else {
            require_once CORE_CLASSES_PATH . $parts[1] . '.php';
        }
    }
}

