<?php

return array(
    
    array ('auth', 'POST', 'AuthController', 'indexAction'),
    array ('auth', 'PUT', 'AuthController', 'indexAction'),
    array ('auth', 'DELETE', 'AuthController', 'indexAction'),
    array ('auth', 'GET', 'AuthController', 'indexAction'),
    
    array ('auth/signin', 'POST', 'AuthController', 'signInAction'),
    array ('auth/signup', 'POST', 'AuthController', 'signUpAction'),
   
);
