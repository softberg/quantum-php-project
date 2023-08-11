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

    $route->get('/auth', 'DropboxController', 'auth');
    $route->get('/confirm', 'DropboxController', 'confirm');
    $route->get('/test', 'DropboxController', 'test');
    $route->get('/image/[:any]', 'DropboxController', 'image');
    $route->get('/upload', 'DropboxController', 'upload');
    $route->get('/list', 'DropboxController', 'list');

    $route->get('[:alpha:2]?/posts', 'PostController', 'posts');
    $route->get('[:alpha:2]?/post/[id=:any]', 'PostController', 'post')->middlewares(['Post']);

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
        $route->get('[:alpha:2]?/my-posts', 'PostController', 'myPosts')->middlewares(['Editor']);
        $route->get('[:alpha:2]?/my-posts/create', 'PostController', 'createFrom')->middlewares(['Editor']);
        $route->post('[:alpha:2]?/my-posts/create', 'PostController', 'create')->middlewares(['Editor']);
        $route->get('[:alpha:2]?/my-posts/amend/[id=:any]', 'PostController', 'amendForm')->middlewares(['Editor', 'Owner']);
        $route->post('[:alpha:2]?/my-posts/amend/[id=:any]', 'PostController', 'amend')->middlewares(['Editor', 'Owner']);
        $route->get('[:alpha:2]?/my-posts/delete/[id=:any]', 'PostController', 'delete')->middlewares(['Editor', 'Owner']);
        $route->get('[:alpha:2]?/my-posts/delete-image/[id=:any]', 'PostController', 'deleteImage')->middlewares(['Editor', 'Owner']);
    })->middlewares(['Auth']);
};
