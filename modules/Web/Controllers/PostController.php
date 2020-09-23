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
 * @since 2.0.0
 */

namespace Modules\Web\Controllers;

use Quantum\Factory\ServiceFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\QtController;
use Quantum\Hooks\HookManager;
use Base\Services\PostService;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class PostController
 * @package Modules\Web\Controllers
 */
class PostController extends QtController
{

    /**
     * Post service
     * @var PostService
     */
    public $postService;

    /**
     * Magic __before
     * @param ServiceFactory $serviceFactory
     * @throws \Exception
     */
    public function __before(ServiceFactory $serviceFactory, ViewFactory $view)
    {
        $this->postService = $serviceFactory->get(PostService::class);

        $view->setLayout('layouts/main');
    }

    /**
     * Get posts
     */
    public function getPosts(Response $response, ViewFactory $view)
    {
        $posts = $this->postService->getPosts();

        $view->setParam('title', 'Posts | ' . config()->get('app_name'));
        $view->setParam('posts', $posts);
        $view->setParam('langs', config()->get('langs'));

        $response->html($view->render('post/post'));
    }

    /**
     * Get post
     * @param  string $lang
     * @param int $id
     * @throws \Exception
     */
    public function getPost($lang, $id, Response $response, ViewFactory $view)
    {
        if (!$id && $lang) {
            $id = $lang;
        }

        $post = $this->postService->getPost($id);

        if (!$post) {
            HookManager::call('pageNotFound', $response);
        }

        $view->setParam('title', $post['title'] . ' | ' . config()->get('app_name'));
        $view->setParam('post', $post);
        $view->setParam('id', $id);
        $view->setParam('langs', config()->get('langs'));

        $response->html($view->render('post/single'));
    }

    /**
     * Amend post
     * @param Request $request
     * @param string $lang
     * @param int|null $id
     * @throws \Exception
     */
    public function amendPost(Request $request, Response $response, ViewFactory $view, $lang, $id = null)
    {
        if ($request->getMethod() == 'GET') {
            $post = [];
            if ($id) {
                $post = $this->postService->getPost($id);
                if (!$post) {
                    HookManager::call('pageNotFound');
                }
            }

            $view->setParam('title', ($post ? $post['title'] : 'New post') . ' | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));

            $response->html($view->render('post/form', ['id' => $id, 'post' => $post]));
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
