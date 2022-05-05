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
    })->name('home');

    $route->get('[:alpha:2]?/about', function (Response $response, ViewFactory $view) {
        $view->setLayout('layouts/landing');

        $view->setParams([
            'title' => 'About | ' . config()->get('app_name'),
            'langs' => config()->get('langs')
        ]);

        $response->html($view->render('about'));
    })->name('about');

    $route->get('[:alpha:2]?/posts', 'PostController', 'getPosts');
    $route->get('[:alpha:2]?/posts/[:any]', 'PostController', 'getPost');

    $route->group('guest', function ($route) {
        $route->add('[:alpha:2]?/signin', 'GET|POST', 'AuthController', 'signin')->name('signin');
        $route->add('[:alpha:2]?/signup', 'GET|POST', 'AuthController', 'signup')->middlewares(['Signup'])->name('signup');
        $route->get('[:alpha:2]?/activate/[:any]', 'AuthController', 'activate')->middlewares(['Activate']);
        $route->add('[:alpha:2]?/forget', 'GET|POST', 'AuthController', 'forget')->middlewares(['Forget']);
        $route->add('[:alpha:2]?/reset/[:any]', 'GET|POST', 'AuthController', 'reset')->middlewares(['Reset']);
        $route->add('[:alpha:2]?/verify/[:any]?', 'GET|POST', 'AuthController', 'verify')->middlewares(['Verify']);
        $route->get('[:alpha:2]?/resend/[:any]?', 'AuthController', 'resend');
    })->middlewares(['Guest']);

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/signout', 'AuthController', 'signout');
        $route->get('[:alpha:2]?/my-posts', 'PostController', 'getMyPosts')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/my-posts/create', 'GET|POST', 'PostController', 'createPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/my-posts/amend/[:any]?', 'GET|POST', 'PostController', 'amendPost')->middlewares(['Editor']);
        $route->get('[:alpha:2]?/my-posts/delete/[:any]', 'PostController', 'deletePost')->middlewares(['Editor']);
        $route->get('[:alpha:2]?/my-posts/delete-image/[:any]','PostController', 'deletePostImage')->middlewares(['Editor']);
    })->middlewares(['Auth']);
};
