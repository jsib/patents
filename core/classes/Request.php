<?php

namespace Core\Request;

class Request
{
    /**
     * Get data from client (browser)
     */
    public function get($arg)
    {
        return $_GET[$arg];
    }
}