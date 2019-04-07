<?php

return function($route){
    $route->add('', 'GET', 'MainController', 'indexAction');
    $route->add('[:alpha]', 'GET', 'MainController', 'indexAction');
};
