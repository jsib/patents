<?php

/* 
 * Provide methods for debugging and error reporting
 */
class Debug
{
    /**
     * Store debug_backtrace() prepared array
     */
    private static $debug = [];
    
    /*
     * Store error number
     */
    private static $errno;
    
    /**
     * Store error file
     */
    private static $errfile;
    
    /**
     * Store error line number
     */
    private static $errline;
    
    /**
     * Means, that this error code not set in  error_reporting
     */
    private static $errno_uknown = false;    
    
    /**
     * Show detailed information about given variable
     * 
     * @param $input   Any kind of variable
     */
    public static function dump($input, $label = '')
    {
        if ($label != '') {
            echo '<h1>'.$label.":</h1>";
        }
        
        //Special condition for boolean values
        if (is_bool($input)) {
            switch ($input) {
                case true:
                    $input = 'true';
                    break;
                case false:
                    $input = 'false';
                    break;
            }
        }
        
        //Show info
        echo "<pre>";
        print_r($input);
        echo "</pre>";
    }
    
    /**
     * Show error message and stop script
     */
    public static function error(
        $error,
        $errno = false,
        $errfile = false,
        $errline = false,
        $errno_uknown = false
    ){
        //Start showing debug_backtrace() information
        $debug = debug_backtrace();
        
        //Remove __CLASS__::error() entry
        self::removeEntries($debug, __CLASS__, 'error');

        //Remove __CLASS__::handleErrors() entry
        self::removeEntries($debug, __CLASS__, 'handleErrors');

        //Reindex array
        self::$debug = array_values($debug);

        //Start catching output
        ob_start();

        //Execute template
        require(VIEWS_PATH . 'debug_backtrace.html.php');

        //Echo catched output
        echo ob_get_clean();

        //Stop script execution
        exit;
    }
    
    /**
     * Answer about error to ajax request
     */
    public static function ajaxError($error)
    {
        echo $error;
        exit;
    }

    /**
     * Handling appeared errors
     * 
     * @param type $errno
     * @param type $errstr
     * @param type $errfile
     * @param type $errline
     * @return boolean
     */
    public static function handleErrors($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            //Means, that this error code is not set in  error_reporting,
            //i.e. this error code should be handled by standart PHP
            //error handling mechanism.
            $errno_uknown = true;
        } else {
            $errno_uknown = false;
        }
        
        //Show error information and stop script
        self::error($errstr, $errno, $errfile, $errline, $errno_uknown);

        //Don't start standart PHP handling mechanism
        return true;
    }
    
    /**
     * Remove entry from debug information array
     * 
     * @param $debug Array Debug input array
     * @param $class string Name of entry class
     * @param $function string Name of entry function
     * @return void
     */
    private static function removeEntries(&$debug, $class, $function)
    {
        //Loop over entries
        foreach ($debug as $key => $entry) {
            //Check entry properties
            if (
                isset($entry['class']) &&
                isset($entry['function']) && 
                $entry['class'] == $class &&
                $entry['function'] == $function
            ) {
                unset($debug[$key]);
            }
        }
    }
}