<?php

namespace Core;

use Core\View;

class Controller
{
    /**
     * @var object $view Access to view object
     * @var object $db Access to database object
     */
    public $view, $db;
    
    function __construct()
    {
        $this->view = new View();
        $this->db = new Database();
        $this->auth = $GLOBALS['auth'];
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