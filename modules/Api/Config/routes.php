<?php

return function ($route) {

    $route->post('api-signin', 'AuthController', 'signin');
    $route->post('api-signup', 'AuthController', 'signup')->middlewares(['Signup']);
    $route->get('api-activate/[:any]', 'AuthController', 'activate')->middlewares(['Activate']);
    $route->post('api-forget', 'AuthController', 'forget')->middlewares(['Forget']);
    $route->post('api-reset/[:any]', 'AuthController', 'reset')->middlewares(['Reset']);
    $route->post('api-verify', 'AuthController', 'verify')->middlewares(['Verify']);
    $route->post('api-resend', 'AuthController', 'resend');

    $route->group('auth', function ($route) {
        $route->get('api-signout', 'AuthController', 'signout');
        $route->get('api-posts', 'PostController', 'getPosts');
        $route->get('api-post/[:num]', 'PostController', 'getPost');
        $route->add('api-post/amend/[:num]?', 'POST|PUT', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->add('api-post/delete/[:num]', 'DELETE', 'PostController', 'deletePost')->middlewares(['Editor']);
    })->middlewares(['Auth']);
    
};
