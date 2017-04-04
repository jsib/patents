<?php

namespace Core\Controller;

use Core\View\View;

class Controller
{
    /**
     * Helper for view object instance
     */
    public $view;
    
    function __construct()
    {
        $this->view = new View();
    }
    
    /**
     * Send answer
     */
    protected function sendJsonAnswer($type, $text = '')
    {
        return json_encode([
            'type' => $type,
            'text' => $text
        ]);
    }

    /**
     * Send custom array
     */
    protected function sendJson($array)
    {
        return json_encode($array);
    }
    
    /**
     * Get specified post data or show error
     */
    public function getData($name)
    {
        if (!isset($_POST[$name])) {
            \ajax_error('There is no variable "$name" in post array.');
        }
        
        return trim($_POST[$name]);
    }
}