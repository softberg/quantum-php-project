<?php

return function ($route) {

    $route->post('[:alpha:2]?/api-signin', 'AuthController', 'signin');
    $route->post('[:alpha:2]?/api-signup', 'AuthController', 'signup')->middlewares(['Signup']);
    $route->get('[:alpha:2]?/api-activate/[:any]', 'AuthController', 'activate')->middlewares(['Activate']);
    $route->post('[:alpha:2]?/api-forget', 'AuthController', 'forget')->middlewares(['Forget']);
    $route->post('[:alpha:2]?/api-reset/[:any]', 'AuthController', 'reset')->middlewares(['Reset']);
    $route->post('[:alpha:2]?/api-verify', 'AuthController', 'verify')->middlewares(['Verify']);
    $route->post('[:alpha:2]?/api-resend', 'AuthController', 'resend')->middlewares(['Resend']);

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/api-signout', 'AuthController', 'signout');
        $route->get('[:alpha:2]?/api-posts', 'PostController', 'getPosts');
        $route->get('[:alpha:2]?/api-post/[:num]', 'PostController', 'getPost');
        $route->add('[:alpha:2]?/api-post/amend/[:num]?', 'POST|PUT', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/api-post/delete/[:num]', 'DELETE', 'PostController', 'deletePost')->middlewares(['Editor']);
    })->middlewares(['Auth']);
    
};
