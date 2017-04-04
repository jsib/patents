<?php

/**
 * This file describes routes to controllers&actions
 */

$route->add('/', 'Main:index');
$route->add('/{country}/{object}/', 'Main:list');
