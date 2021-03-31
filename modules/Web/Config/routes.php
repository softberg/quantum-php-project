<?php

use Quantum\Factory\ViewFactory;
use Quantum\Http\Response;

return function ($route) {
    $route->get('[:alpha:2]?', function (Response $response, ViewFactory $view) {
        $view->setLayout('layouts/landing');

        $view->setParams([
            'title' => 'Welcome | ' . config()->get('app_name'),
            'langs' => config()->get('langs')
        ]);

        $response->html($view->render('index'));
    });

    $route->get('[:alpha:2]?/about', function (Response $response, ViewFactory $view) {
        $view->setLayout('layouts/landing');

        $view->setParams([
            'title' => 'About | ' . config()->get('app_name'),
            'langs' => config()->get('langs')
        ]);

        $response->html($view->render('about'));
    });

    $route->group('guest', function ($route) {
        $route->add('[:alpha:2]?/signin', 'GET|POST', 'AuthController', 'signin');
        $route->add('[:alpha:2]?/signup', 'GET|POST', 'AuthController', 'signup')->middlewares(['Signup']);
        $route->get('[:alpha:2]?/activate/[:any]', 'AuthController', 'activate')->middlewares(['Activate']);
        $route->add('[:alpha:2]?/forget', 'GET|POST', 'AuthController', 'forget')->middlewares(['Forget']);
        $route->add('[:alpha:2]?/reset/[:any]', 'GET|POST', 'AuthController', 'reset')->middlewares(['Reset']);
        $route->add('[:alpha:2]?/verify/[:any]?', 'GET|POST', 'AuthController', 'verify')->middlewares(['Verify']);
        $route->get('[:alpha:2]?/resend/[:any]?', 'AuthController', 'resend');
    })->middlewares(['Guest']);

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/signout', 'AuthController', 'signout');
        $route->get('[:alpha:2]?/posts', 'PostController', 'getPosts');
        $route->get('[:alpha:2]?/post/[:num]', 'PostController', 'getPost');
        $route->add('[:alpha:2]?/post/amend/[:num]?', 'GET|POST|PUT', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->get('[:alpha:2]?/post/delete/[:num]', 'PostController', 'deletePost')->middlewares(['Editor']);
    })->middlewares(['Auth']);
};
