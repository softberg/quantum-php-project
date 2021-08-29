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
 * @since 2.5.0
 */

namespace Modules\Web\Controllers;

use Quantum\Factory\ServiceFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Hooks\HookManager;
use Base\Services\PostService;
use Quantum\Mvc\QtController;
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
     * @var \Base\Services\PostService
     */
    public $postService;

    /**
     * Magic __before
     * @param \Quantum\Factory\ServiceFactory $serviceFactory
     * @param \Quantum\Factory\ViewFactory $view
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\ServiceException
     * @throws \ReflectionException
     */
    public function __before(ServiceFactory $serviceFactory, ViewFactory $view)
    {
        $this->postService = $serviceFactory->get(PostService::class);
        $view->setLayout('layouts/main');
    }

    /**
     * Get posts action
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\HookException
     * @throws \Quantum\Exceptions\ViewException
     * @throws \ReflectionException
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
     * Get post action
     * @param string $lang
     * @param int $id
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\HookException
     * @throws \Quantum\Exceptions\ViewException
     * @throws \ReflectionException
     */
    public function getPost(string $lang, int $id, Response $response, ViewFactory $view)
    {
        if (!$id && $lang) {
            $id = $lang;
        }

        $post = $this->postService->getPost($id);

        if (!$post) {
            HookManager::call('pageNotFound');
        }

        $view->setParam('title', $post['title'] . ' | ' . config()->get('app_name'));
        $view->setParam('post', $post);
        $view->setParam('id', $id);
        $view->setParam('langs', config()->get('langs'));

        $response->html($view->render('post/single'));
    }

    /**
     * Create post action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     * @throws \Gumlet\ImageResizeException
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\FileUploadException
     * @throws \Quantum\Exceptions\HookException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \Quantum\Exceptions\ViewException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function createPost(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->isMethod('post')) {
            $postData = [
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'image' => null,
                'author' => auth()->user()->getFieldValue('email'),
                'updated_at' => date('m/d/Y H:i'),
            ];

            if ($request->hasFile('image')) {
                $imageName = $this->postService->saveImage($request->getFile('image'), slugify($request->get('title')));
                $postData['image'] = base_url() . '/uploads/' . $imageName;
            }

            $this->postService->addPost($postData);
            redirect(base_url() . '/' . current_lang() . '/posts');
        } else {
            $view->setParam('title', 'New post | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));

            $response->html($view->render('post/form'));
        }

    }

    /**
     * Amend post action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     * @param string $lang
     * @param int|null $id
     * @throws \Gumlet\ImageResizeException
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\FileUploadException
     * @throws \Quantum\Exceptions\HookException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \Quantum\Exceptions\ViewException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function amendPost(Request $request, Response $response, ViewFactory $view, string $lang, int $id = null)
    {
        if ($request->isMethod('post')) {
            $postData = [
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'author' => auth()->user()->getFieldValue('email'),
                'updated_at' => date('m/d/Y H:i'),
            ];

            $post = $this->postService->getPost($id);

            if (!$post) {
                redirect(base_url() . '/' . current_lang() . '/posts');
            }

            if ($request->hasFile('image')) {
                if ($post['image']) {
                    $this->postService->deleteImage($post['image']);
                }

                $imageName = $this->postService->saveImage($request->getFile('image'), slugify($request->get('title')));
                $postData['image'] = base_url() . '/uploads/' . $imageName;
            }

            $this->postService->updatePost($id, $postData);
            redirect(base_url() . '/' . current_lang() . '/posts');

        } else {
            $post = $this->postService->getPost($id);
            $view->setParam('id', $id);

            if (!$post) {
                HookManager::call('pageNotFound');
            }

            $view->setParam('title', $post['title'] . ' | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));

            $response->html($view->render('post/form', ['post' => $post]));
        }
    }

    /**
     * Delete post action
     * @param string $lang
     * @param int $id
     * @throws \Quantum\Exceptions\DiException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function deletePost(string $lang, int $id)
    {
        $post = $this->postService->getPost($id);

        if ($post['image']) {
            $this->postService->deleteImage($post['image']);
        }

        $this->postService->deletePost($id);

        redirect(base_url() . '/' . current_lang() . '/posts');
    }

    /**
     * Delete post image action
     * @param string $lang
     * @param int $id
     * @throws \Quantum\Exceptions\DiException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function deletePostImage(string $lang, int $id)
    {
        $post = $this->postService->getPost($id);

        if ($post['image']) {
            $this->postService->deleteImage($post['image']);
        }

        $post['image'] = null;
        $this->postService->updatePost($id, $post);

        redirect(base_url() . '/' . current_lang() . '/posts');
    }

}
