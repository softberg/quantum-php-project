<?php

namespace Modules\Main\Controllers;

use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Factory\ServiceFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Libraries\Lang\Lang;
use Modules\Main\Services\Post;

class MainController extends Qt_Controller
{

    public $view;

    public $service;

    public $csrfVerification = false;

    public function __before(ServiceFactory $service, ViewFactory $view)
    {
        $this->view = $view;

        $this->service = $service;

        $lang = Request::getSegment(1);
        Lang::init($lang);

        $this->view->setLayout('layouts/main');
    }

    public function index()
    {
        $this->view->render('index');
    }

    public function about()
    {
        $this->view->render('about');
    }

    public function getPosts()
    {
        $postService = $this->service->get(Post::class);
        $posts = $postService->getPosts();

        $this->view->render('post/post', ['posts' => $posts]);
    }

    public function getPost($id)
    {
        $postService = $this->service->get(Post::class);
        $post = $postService->getPost($id);

        $this->view->render('post/single', ['id' => $id, 'post' => $post]);
    }

    public function amendPost($id = null)
    {
        $postService = $this->service->get(Post::class);
        $post = [
            'title' => 'Mickey Mouse',
            'content' => 'Mickey Mouse is a cartoon character who has become an icon for the Walt Disney Company. Mickey Mouse is short for Mitchell Mouse. It was created in 1928 by Walt Disney and Ub Iwerks and voiced by Walt Disney.'
        ];

        if ($id) {
            $postService->updatePost($id, $post);
        } else {
            $postService->addPost($post);
        }

        $posts = $postService->getPosts();
        $this->view->render('post/post', ['posts' => $posts]);
    }

}
