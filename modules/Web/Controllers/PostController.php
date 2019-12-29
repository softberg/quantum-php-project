<?php

namespace Modules\Web\Controllers;

use Quantum\Factory\ServiceFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;
use Quantum\Hooks\HookManager;
use Base\Services\PostService;
use Quantum\Http\Request;

class PostController extends Qt_Controller
{

    public $view;
    public $postService;

    public function __before(ServiceFactory $serviceFactory, ViewFactory $view)
    {
        $this->postService = $serviceFactory->get(PostService::class);

        $this->view = $view;
        $this->view->setLayout('layouts/main');
    }

    public function getPosts()
    {
        $posts = $this->postService->getPosts();
        $this->view->render('post/post', ['posts' => $posts]);
    }

    public function getPost($lang, $id)
    {
        if(!$id && $lang) {
            $id = $lang;
        }
        $post = $this->postService->getPost($id);

        if (!$post) {
            HookManager::call('pageNotFound');
        }

        $this->view->render('post/single', ['id' => $id, 'post' => $post]);
    }

    public function amendPost(Request $request, $lang, $id = null)
    {
        if ($request->getMethod() == 'GET') {
            $post = [];
            if ($id) {
                $post = $this->postService->getPost($id);
                if (!$post) {
                    HookManager::call('pageNotFound');
                }
            }

            $this->view->render('post/form', ['id' => $id, 'post' => $post]);
        } else {
            $post = [
                'title' => $request->get('title'),
                'content' => $request->get('content'),
            ];

            if ($id) {
                $this->postService->updatePost($id, $post);
            } else {
                $this->postService->addPost($post);
            }

            redirect(base_url() . '/' . current_lang() . '/posts');
        }
    }

    public function deletePost($lang, $id)
    {
        $this->postService->deletePost($id);
        redirect(base_url() . '/' . current_lang() . '/posts');
    }

}
