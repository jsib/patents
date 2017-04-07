<?php

/**
 * This file describes routes to controllers&actions
 */

$route->add('/', 'Main:index');                     //Home page
$route->add('/{country}/{object}/', 'Main:list');   //List countries
$route->add('/{country}/{object}/add', 'Main:add'); //Add item

//Sign in, sign out, registration
$route->add('/sign_in/', 'SignIn:form');
$route->add('/sign_in/check/', 'SignIn:check');
$route->add('/sign_out/', 'SignIn:signOut');