<?php

return function ($route) {
    $route->group("openapi", function ($route) {
        $route->get("docs", function (Quantum\Http\Response $response) {
            $response->html(partial("openApi/openApi"));
        });

        $route->get("spec", function (Quantum\Http\Response $response) {
            $fs = Quantum\Di\Di::get(Quantum\Libraries\Storage\FileSystem::class);
            $response->json((array) json_decode($fs->get(modules_dir() . "\Api\Resources\openapi\spec.json")));
        });
    });

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
        $route->get('[:alpha:2]?/my-posts', 'PostController', 'myPosts')->middlewares(['Editor']);
        $route->post('[:alpha:2]?/my-posts/create', 'PostController', 'create')->middlewares(['Editor']);
        $route->add('[:alpha:2]?/my-posts/amend/[id=:any]', 'PUT', 'PostController', 'amend')->middlewares(['Editor', 'Owner']);
        $route->add('[:alpha:2]?/my-posts/delete/[id=:any]', 'DELETE', 'PostController', 'delete')->middlewares(['Editor', 'Owner']);
        $route->add('[:alpha:2]?/my-posts/delete-image/[id=:any]', 'DELETE', 'PostController', 'deleteImage')->middlewares(['Editor', 'Owner']);
    })->middlewares(['Auth']);
};
