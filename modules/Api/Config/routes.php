<?php

return function ($route) {
    $route->get('[:alpha:2]?/posts', 'PostController', 'getPosts');
    $route->get('[:alpha:2]?/post/[id=:any]', 'PostController', 'getPost')->middlewares(['Post']);

    $route->post('[:alpha:2]?/signin', 'AuthController', 'signin');
    $route->post('[:alpha:2]?/signup', 'AuthController', 'signup')->middlewares(['Signup']);
    $route->post('[:alpha:2]?/forget', 'AuthController', 'forget')->middlewares(['Forget']);
    $route->get('[:alpha:2]?/activate/[token=:any]', 'AuthController', 'activate')->middlewares(['Activate']);
    $route->post('[:alpha:2]?/reset/[token=:any]', 'AuthController', 'reset')->middlewares(['Reset']);
    $route->get('[:alpha:2]?/resend/[code=:any]', 'AuthController', 'resend')->middlewares(['Resend']);
    $route->post('[:alpha:2]?/verify', 'AuthController', 'verify')->middlewares(['Verify']);

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/me', 'AuthController', 'me');
        $route->get('[:alpha:2]?/signout', 'AuthController', 'signout')->middlewares(['Signout']);
        $route->get('[:alpha:2]?/my-posts', 'PostController', 'getMyPosts')->middlewares(['Editor']);
        $route->post('[:alpha:2]?/my-posts/create', 'PostController', 'createPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/my-posts/amend/[id=:any]', 'PUT', 'PostController', 'amendPost')->middlewares(['Editor', 'Owner']);
        $route->add('[:alpha:2]?/my-posts/delete/[id=:any]', 'DELETE', 'PostController', 'deletePost')->middlewares(['Editor', 'Owner']);
        $route->add('[:alpha:2]?/my-posts/delete-image/[id=:any]', 'DELETE', 'PostController', 'deletePostImage')->middlewares(['Editor', 'Owner']);
    })->middlewares(['Auth']);
};
