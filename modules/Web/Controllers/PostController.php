<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.9.9
 */

namespace Modules\Web\Controllers;

use Quantum\Factory\ServiceFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;
use Quantum\Hooks\HookManager;
use Base\Services\PostService;
use Quantum\Http\Request;

/**
 * Class PostController
 * @package Modules\Web\Controllers
 */
class PostController extends Qt_Controller
{

    /**
     * View
     * @var ViewFactory
     */
    public $view;

    /**
     * POst service
     * @var PostService
     */
    public $postService;

    /**
     * Magic __before
     * @param ServiceFactory $serviceFactory
     * @param ViewFactory $view
     * @throws \Exception
     */
    public function __before(ServiceFactory $serviceFactory, ViewFactory $view)
    {
        $this->postService = $serviceFactory->get(PostService::class);

        $this->view = $view;
        
        $this->view->setLayout('layouts/main');
        
        $this->view->share(['title' => 'Quantum PHP Framework']);
    }

    /**
     * Get posts
     */
    public function getPosts()
    {
        $posts = $this->postService->getPosts();
        $this->view->render('post/post', ['posts' => $posts]);
    }

    /**
     * Get post
     * @param  string $lang
     * @param int $id
     * @throws \Exception
     */
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

    /**
     * Amend post
     * @param Request $request
     * @param string $lang
     * @param int|null $id
     * @throws \Exception
     */
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

    /**
     * Delete post
     * @param string $lang
     * @param int $id
     * @throws \Exception
     */
    public function deletePost($lang, $id)
    {
        $this->postService->deletePost($id);
        redirect(base_url() . '/' . current_lang() . '/posts');
    }

}
