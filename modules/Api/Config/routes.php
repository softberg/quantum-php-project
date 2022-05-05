<?php

return function ($route) {

    $route->post('[:alpha:2]?/api-signin', 'AuthController', 'signin');
    $route->post('[:alpha:2]?/api-signup', 'AuthController', 'signup')->middlewares(['Signup']);
    $route->get('[:alpha:2]?/api-activate/[:any]', 'AuthController', 'activate')->middlewares(['Activate']);
    $route->post('[:alpha:2]?/api-forget', 'AuthController', 'forget')->middlewares(['Forget']);
    $route->post('[:alpha:2]?/api-reset/[:any]', 'AuthController', 'reset')->middlewares(['Reset']);
    $route->post('[:alpha:2]?/api-verify', 'AuthController', 'verify')->middlewares(['Verify']);
    $route->post('[:alpha:2]?/api-resend', 'AuthController', 'resend')->middlewares(['Resend']);

    $route->get('[:alpha:2]?/api-posts', 'PostController', 'getPosts');
    $route->get('[:alpha:2]?/api-posts/[:any]', 'PostController', 'getPost');

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/api-signout', 'AuthController', 'signout');
        $route->add('[:alpha:2]?/api-my-posts', 'POST','PostController', 'getMyPosts')->middlewares(['Editor']);
        $route->post('[:alpha:2]?/api-my-posts/create', 'PostController', 'createPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/api-my-posts/amend/[:any]', 'PUT', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/api-my-posts/delete/[:any]', 'DELETE', 'PostController', 'deletePost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/api-my-posts/delete-image/[:any]', 'DELETE', 'PostController', 'deletePostImage')->middlewares(['Editor']);
    })->middlewares(['Auth']);
    
};
