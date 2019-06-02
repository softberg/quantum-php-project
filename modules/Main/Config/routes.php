<?php

return function($route) {
    $route->add('', 'GET', 'MainController', 'index');
    $route->add('about', 'GET', 'MainController', 'about');

    $route->group('post', function($route) {
        $route->add('posts', 'GET', 'MainController', 'getPosts');
        $route->add('post/[:num]', 'GET', 'MainController', 'getPost');
        $route->add('post/amend/[:num]?', 'POST|PUT', 'MainController', 'amendPost')->middlewares(['editor']);
    })->middlewares(['blogger']);
};
