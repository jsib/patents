<?php

use Core\Auth;

//Set sign in and sign in check routes
$auth->setSignInRoute('/sign_in/');
$auth->setSignInCheckRoute('/sign_in/check/');

//Example, how to define anonymous route
//Auth::addAnonymousRoute();   


