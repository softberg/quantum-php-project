<?php

return function ($route) {

    $route->post('[:alpha:2]?/api-signin', 'AuthController', 'signin');
    $route->post('[:alpha:2]?/api-signup', 'AuthController', 'signup')->middlewares(['Signup']);
    $route->post('[:alpha:2]?/api-forget', 'AuthController', 'forget')->middlewares(['Forget']);
    $route->get('[:alpha:2]?/api-activate/[token=:any]', 'AuthController', 'activate')->middlewares(['Activate']);
    $route->post('[:alpha:2]?/api-reset/[token=:any]', 'AuthController', 'reset')->middlewares(['Reset']);
    $route->get('[:alpha:2]?/api-resend/[code=:any]', 'AuthController', 'resend')->middlewares(['Resend']);
    $route->post('[:alpha:2]?/api-verify', 'AuthController', 'verify')->middlewares(['Verify']);

    $route->get('[:alpha:2]?/api-posts', 'PostController', 'getPosts');
    $route->get('[:alpha:2]?/api-post/[id=:any]', 'PostController', 'getPost')->middlewares(['Post']);

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/api-me', 'AuthController', 'me');
        $route->get('[:alpha:2]?/api-signout', 'AuthController', 'signout')->middlewares(['Signout']);
        $route->get('[:alpha:2]?/api-my-posts', 'PostController', 'getMyPosts')->middlewares(['Editor']);
        $route->post('[:alpha:2]?/api-my-posts/create', 'PostController', 'createPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/api-my-posts/amend/[id=:any]', 'PUT', 'PostController', 'amendPost')->middlewares(['Editor', 'Owner']);
        $route->add('[:alpha:2]?/api-my-posts/delete/[id=:any]', 'DELETE', 'PostController', 'deletePost')->middlewares(['Editor', 'Owner']);
        $route->add('[:alpha:2]?/api-my-posts/delete-image/[id=:any]', 'DELETE', 'PostController', 'deletePostImage')->middlewares(['Editor', 'Owner']);
    })->middlewares(['Auth']);
    
};
