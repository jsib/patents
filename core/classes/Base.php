<?php

namespace Core;

/**
 * Base class
 */
class Base
{
    /**
     * @var object $db Store database object
     * @var object $db Store view object
     */
    protected $db;
    protected $view;
    
    public function __construct()
    {
        $this->db = new Database();
        $this->view = new View();
    }
}