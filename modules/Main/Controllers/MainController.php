<?php

namespace Modules\Main\Controllers;

use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Quantum\Factory\Factory;
use Quantum\Libraries\Lang\Lang;
use Modules\Main\Services\Post;

class MainController extends Qt_Controller {

    public $csrfVerification = false;

    public function __construct() {
        $this->setLayout('layouts/main');
    }

    public function __before() {
        $lang = Request::getSegment(1);
        Lang::init($lang);
    }

    public function index() {
        $this->render('index');
    }

    public function about() {
        $this->render('about');
    }

    public function getPosts(Factory $factory) {
        $postModel = $factory->getService(Post::class);
        $posts = $postModel->getPosts();
        $this->render('post/post', ['posts' => $posts]);
    }

    public function getPost(Factory $factory, $id) {
        $postModel = $factory->getService(Post::class);
        $post = $postModel->getPost($id);
        $this->render('post/single', ['id' => $id, 'post' => $post]);
    }

    public function amendPost(Request $request, Factory $factory, $id = null) {
        $postModel = $factory->getService(Post::class);
        $post = [
            'title' => 'Mickey Mouse',
            'content' => 'Mickey Mouse is a cartoon character who has become an icon for the Walt Disney Company. Mickey Mouse is short for Mitchell Mouse. It was created in 1928 by Walt Disney and Ub Iwerks and voiced by Walt Disney.'
        ];

        if ($id) {
            $postModel->updatePost($id, $post);
        } else {
            $postModel->addPost($post);
        }

        $posts = $postModel->getPosts();
        $this->render('post/post', ['posts' => $posts]);
    }

}
