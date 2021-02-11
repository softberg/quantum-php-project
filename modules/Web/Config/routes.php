<?php

return function ($route) {
    $route->add('[:alpha:2]?', 'GET', 'MainController', 'index');
    $route->add('[:alpha:2]?/about', 'GET', 'MainController', 'about');

    $route->group('guest', function ($route) {
        $route->add('[:alpha:2]?/signin', 'GET|POST', 'AuthController', 'signin');
        $route->add('[:alpha:2]?/signup', 'GET|POST', 'AuthController', 'signup')->middlewares(['Signup']);
        $route->add('[:alpha:2]?/activate/[:any]', 'GET', 'AuthController', 'activate')->middlewares(['Activate']);
        $route->add('[:alpha:2]?/forget', 'GET|POST', 'AuthController', 'forget')->middlewares(['Forget']);
        $route->add('[:alpha:2]?/reset/[:any]', 'GET|POST', 'AuthController', 'reset')->middlewares(['Reset']);
        $route->add('[:alpha:2]?/verify/[:any]?', 'GET|POST', 'AuthController', 'verify')->middlewares(['Verify']);
        $route->add('[:alpha:2]?/resend/[:any]?', 'GET', 'AuthController', 'resend');
    })->middlewares(['Guest']);

    $route->group('auth', function ($route) {
        $route->add('[:alpha:2]?/signout', 'GET', 'AuthController', 'signout');
        $route->add('[:alpha:2]?/posts', 'GET', 'PostController', 'getPosts');
        $route->add('[:alpha:2]?/post/[:num]', 'GET', 'PostController', 'getPost');
        $route->add('[:alpha:2]?/post/amend/[:num]?', 'GET|POST|PUT', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/post/delete/[:num]', 'GET', 'PostController', 'deletePost')->middlewares(['Editor']);
    })->middlewares(['Auth']);
};
