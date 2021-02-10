<?php

return function ($route) {
    $route->add('api-signin', 'POST', 'AuthController', 'signin');
    $route->add('api-signup', 'POST', 'AuthController', 'signup')->middlewares(['Signup']);
    $route->add('api-activate/[:any]', 'GET', 'AuthController', 'activate')->middlewares(['Activate']);
    $route->add('api-forget', 'POST', 'AuthController', 'forget')->middlewares(['Forget']);
    $route->add('api-reset/[:any]', 'POST', 'AuthController', 'reset')->middlewares(['Reset']);
    $route->add('api-verify', 'POST', 'AuthController', 'verify')->middlewares(['Verify']);
    $route->add('api-resend', 'POST', 'AuthController', 'resend');

    $route->group('auth', function ($route) {
        $route->add('api-signout', 'GET', 'AuthController', 'signout');
        $route->add('api-posts', 'GET', 'PostController', 'getPosts');
        $route->add('api-post/[:num]', 'GET', 'PostController', 'getPost');
        $route->add('api-post/amend/[:num]?', 'POST|PUT', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->add('api-post/delete/[:num]', 'DELETE', 'PostController', 'deletePost')->middlewares(['Editor']);
    })->middlewares(['Auth']);
    
};
