<?php

return function ($route) {
    $route->add('api-signin', 'POST', 'AuthController', 'signin');
    $route->add('api-signup', 'POST', 'AuthController', 'signup')->middlewares(['signup']);
    $route->add('api-activate/[:any]', 'GET', 'AuthController', 'activate')->middlewares(['activate']);
    $route->add('api-forget', 'POST', 'AuthController', 'forget')->middlewares(['forget']);
    $route->add('api-reset/[:any]', 'POST', 'AuthController', 'reset')->middlewares(['reset']);

    $route->group('auth', function ($route) {
        $route->add('api-signout', 'GET', 'AuthController', 'signout');
        $route->add('api-posts', 'GET', 'PostController', 'getPosts');
        $route->add('api-post/[:num]', 'GET', 'PostController', 'getPost');
        $route->add('api-post/amend/[:num]?', 'POST|PUT', 'PostController', 'amendPost')->middlewares(['editor']);
        $route->add('api-post/delete/[:num]', 'DELETE', 'PostController', 'deletePost')->middlewares(['editor']);
    })->middlewares(['auth']);
    
    $route->group('guest', function ($route) {
        $route->add('api-signout-test', 'GET', 'AuthController', 'signout');
    })->middlewares(['guest']);
};
