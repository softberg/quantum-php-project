<?php

return function ($route) {
    $route->add('[:alpha:2]?', 'GET', 'MainController', 'index');
    $route->add('[:alpha:2]?/about', 'GET', 'MainController', 'about');

    $route->group('guest', function ($route) {
        $route->add('[:alpha:2]?/signin', 'GET|POST', 'AuthController', 'signin');
        $route->add('[:alpha:2]?/signup', 'GET|POST', 'AuthController', 'signup')->middlewares(['signup']);
        $route->add('[:alpha:2]?/forget', 'GET|POST', 'AuthController', 'forget')->middlewares(['forget']);
        $route->add('[:alpha:2]?/reset/[:any]', 'GET|POST', 'AuthController', 'reset')->middlewares(['reset']);
    })->middlewares(['guest']);

    $route->group('auth', function ($route) {
        $route->add('[:alpha:2]?/signout', 'GET', 'AuthController', 'signout');
        $route->add('[:alpha:2]?/posts', 'GET', 'PostController', 'getPosts');
        $route->add('[:alpha:2]?/post/[:num]', 'GET', 'PostController', 'getPost');
        $route->add('[:alpha:2]?/post/amend/[:num]?', 'GET|POST|PUT', 'PostController', 'amendPost')->middlewares(['editor']);
        $route->add('[:alpha:2]?/post/delete/[:num]', 'GET', 'PostController', 'deletePost')->middlewares(['editor']);
    })->middlewares(['auth']);
};
