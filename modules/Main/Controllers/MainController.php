<?php

namespace Modules\Main\Controllers;

use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Quantum\Libraries\Lang\Lang;
use Modules\Main\Models\Post;

class MainController extends Qt_Controller {

    public $postModel;

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

    public function getPosts() {
        $this->postModel = $this->modelFactory('Post');
        $posts = $this->postModel->getPosts();
        $this->render('post/post', ['posts' => $posts]);
    }

    public function getPost($id) {
        $this->postModel = $this->modelFactory('Post');
        $post = $this->postModel->getPost($id);
        $this->render('post/single', ['id'=> $id, 'post' => $post]);
    }

    public function amendPost(Request $request, $id = null) {
        $this->postModel = $this->modelFactory('Post');
        $post = [
            'title' => 'Mickey Mouse',
            'content' => 'Mickey Mouse is a cartoon character who has become an icon for the Walt Disney Company. Mickey Mouse is short for Mitchell Mouse. It was created in 1928 by Walt Disney and Ub Iwerks and voiced by Walt Disney.'
        ];
        
        if($id) {
            $this->postModel->updatePost($id, $post);
        } else {
            $this->postModel->addPost($post);
        }
        
        $posts = $this->postModel->getPosts();
        $this->render('post/post', ['posts' => $posts]);
    }

}
