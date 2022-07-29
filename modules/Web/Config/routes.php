<?php

use Quantum\Factory\ViewFactory;
use Quantum\Http\Response;

return function ($route) {
    $route->get('[:alpha:2]?', function (Response $response, ViewFactory $view) {
        $view->setLayout('layouts/main');

        $view->setParams([
            'title' => config()->get('app_name'),
            'langs' => config()->get('langs')
        ]);

        $response->html($view->render('index'));
    })->name('home');

    $route->get('[:alpha:2]?/about', function (Response $response, ViewFactory $view) {
        $view->setLayout('layouts/main');

        $view->setParams([
            'title' => t('common.about') . ' | ' . config()->get('app_name'),
            'langs' => config()->get('langs')
        ]);

        $response->html($view->render('about'));
    })->name('about');

    $route->get('[:alpha:2]?/posts', 'PostController', 'getPosts');
    $route->get('[:alpha:2]?/post/[id=:any]', 'PostController', 'getPost')->middlewares(['Post']);

    $route->group('guest', function ($route) {
        $route->add('[:alpha:2]?/signin', 'GET|POST', 'AuthController', 'signin')->name('signin');
        $route->add('[:alpha:2]?/signup', 'GET|POST', 'AuthController', 'signup')->middlewares(['Signup'])->name('signup');
        $route->get('[:alpha:2]?/activate/[token=:any]', 'AuthController', 'activate')->middlewares(['Activate']);
        $route->add('[:alpha:2]?/forget', 'GET|POST', 'AuthController', 'forget')->middlewares(['Forget']);
        $route->add('[:alpha:2]?/reset/[token=:any]', 'GET|POST', 'AuthController', 'reset')->middlewares(['Reset']);
        $route->get('[:alpha:2]?/resend/[code=:any]', 'AuthController', 'resend')->middlewares(['Resend']);
        $route->add('[:alpha:2]?/verify/[code=:any]?', 'GET|POST', 'AuthController', 'verify')->middlewares(['Verify']);
    })->middlewares(['Guest']);

    $route->group('auth', function ($route) {
        $route->get('[:alpha:2]?/signout', 'AuthController', 'signout');
        $route->get('[:alpha:2]?/my-posts', 'PostController', 'getMyPosts')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/my-posts/create', 'GET|POST', 'PostController', 'createPost')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/my-posts/amend/[id=:any]', 'GET|POST', 'PostController', 'amendPost')->middlewares(['Editor', 'Owner']);
        $route->get('[:alpha:2]?/my-posts/delete/[id=:any]', 'PostController', 'deletePost')->middlewares(['Editor', 'Owner']);
        $route->get('[:alpha:2]?/my-posts/delete-image/[id=:any]', 'PostController', 'deletePostImage')->middlewares(['Editor', 'Owner']);
    })->middlewares(['Auth']);
};
