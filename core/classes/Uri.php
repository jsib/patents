<?php

namespace Core;

/* 
 * Realize methods for working with URIs: parse, etc
 */
class Uri
{
    /**
     * URI transormed to array
     */
    public $uri = [];
    
    /**
     * Parse URI into array
    */
    public function parse($uri_str = null)
    {
        //If parameter is not presented then take uri from client
        if ($uri_str === null) {
            $uri_str = $_SERVER['REQUEST_URI'];
        }
        
        //Perform first separation to two main parts
        $path_params = explode("?", $uri_str);
        
        $path_str = $path_params[0];
        
        if (count($path_params) > 1) {
            $params_str = $path_params[1];
        } else {
            $params_str = null;
        }
        
        //Now separate path to pieces        
        $path = explode("/", $path_str);
        
        //Remove zero empty piece
        unset($path[0]);
        
        //Remove last piece if it's empty
        if ($path[count($path)] == "") {
            unset($path[count($path)]);
        }
        
        //Reindex array
        $path = array_values($path);
        
        //Pass result
        $this->uri = ['path' => $path];
        
        return $this;        
    }
    
    /**
     * Return path of uri in array
     * 
     * @param int $key_number
     * 
     * @return mixed Return string with piece of uri path.
     *    Or false if this uri piece was not found.
     */
    public function getPath($key_number = null)
    {
        //Return full path array
        if ($key_number === null) {
            return $this->uri['path'];
        }
        
        //Return string with certain piece of path array
        if (isset($this->uri['path'][$key_number])) {
            return $this->uri['path'][$key_number];
        }
        
        //No element in uri array with this key was found
        return false;
        
    }
    
    /**
     * Get full client uri
     */
    public function get()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    //Searching in for argument with $argument_name in $uri
    private function uriFindArgument($argument_name, $uri){
        if(preg_match("/\?$argument_name\=/", $uri) || preg_match("/\&$argument_name\=/", $uri)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Change value of $argument_name to $argument_value in $uri.
     * If there was no argument with $argument_name in $uri,
     * then it will be added to resulting string.
     */
    public function uriChange($argument_name, $argument_value, $uri)
    {
        //Uri includes "?"
        if( preg_match("/\?/", $uri) ){
            //Uri contains searching argument
            if( $this->uriFindArgument($argument_name, $uri) ) {
                //Changing value of argument (if value is presented)
                if($argument_value != "") {
                    $uri = preg_replace("/\?$argument_name\=[^\&\?]+/", "?$argument_name=$argument_value", $uri);
                    $uri = preg_replace("/\&$argument_name\=[^\&\?]+/", "&$argument_name=$argument_value", $uri);
                //Argument deletion (if value is empty)
                } else {
                    $uri = preg_replace("/\?$argument_name\=[^\&\?]+/", "?", $uri);
                    //In case of argument leads after '?' and there were other
                    //arguments after this argument (which always prepend by &)
                    preg_replace("/\?\&/", "?", $uri); 
                    $uri = preg_replace("/\&$argument_name\=[^\&\?]+/", "", $uri);
                }
            //Uri doesn't contain searching argument
            }else{
                $uri = "{$uri}&{$argument_name}={$argument_value}";
            }
        //Uri doesn't contain "?", means that we just add argument with value
        }else{
            $uri = "{$uri}?{$argument_name}={$argument_value}";
        }
        
        return $uri;
    }

    /**
     * Change value of argument in $_SERVER['URI_REQUEST'] and return result
     */
    function uriMake($argument_name = false, $argument_value = "")
    {
        if( !$argument_name ) {
            return $_SERVER['REQUEST_URI'];
        } else {
            if( !is_array($argument_name) ){
                return $this->uriChange($argument_name, $argument_value, $_SERVER['REQUEST_URI']);
            } else {
                $arguments = $argument_name;
                $uri = $_SERVER['REQUEST_URI'];
                foreach ($arguments as $name=>$value) {
                    $uri = $this->uriChange($name, $value, $uri);
                }

                return $uri;
            }
        }
    }
}