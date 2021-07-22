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
use Quantum\Libraries\Upload\File;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Di\Di;


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
     * @throws \Quantum\Exceptions\ServiceException
     */
    public function __before(ServiceFactory $serviceFactory, ViewFactory $view)
    {
        $this->postService = $serviceFactory->get(PostService::class);
        $view->setLayout('layouts/main');
    }

    /**
     * Get posts
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
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
     * @param $lang
     * @param $id
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     * @throws \Quantum\Exceptions\HookException
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
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     * @param string $lang
     * @param int|null $id
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\HookException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function amendPost(Request $request, Response $response, ViewFactory $view, string $lang, int $id = null)
    {
        if ($request->isMethod('post')) {

            $post = [
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'image' => null,
                'author' => auth()->user()->getFieldValue('email'),
                'updated_at' => date('m/d/Y H:i'),
            ];

            if ($request->hasFile('image')) {

                $imageName = slugify($request->get('title'));

                if ($id) {
                    $post = $this->postService->getPost($id);
                    $this->deleteImage($post);

                    $imageName = slugify($post['title']);
                }

                $post['image'] = $this->saveImage($request->getFile('image'), $imageName .'-'. random_number());
            }

            if ($id) {
                if (!$request->hasFile('image')) {
                    unset($post['image']);
                }
                
                $this->postService->updatePost($id, $post);
            } else {
                $this->postService->addPost($post);
            }

            redirect(base_url() . '/' . current_lang() . '/posts');
        } else {
            $post = [];

            if ($id) {
                $post = $this->postService->getPost($id);
                $view->setParam('id', $id);

                if (!$post) {
                    HookManager::call('pageNotFound');
                }
            }

            $view->setParam('title', ($post ? $post['title'] : 'New post') . ' | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));

            $response->html($view->render('post/form', ['post' => $post]));
        }
    }

    /**
     * Delete post
     * @param string $lang
     * @param int $id
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function deletePost(string $lang, int $id)
    {
        $post = $this->postService->getPost($id);
        $this->deleteImage($post);
        $this->postService->deletePost($id);

        redirect(base_url() . '/' . current_lang() . '/posts');
    }

    private function saveImage($image, $imageName)
    {
        $file = new File($image);
        $file->setName($imageName);
        $file->save(uploads_dir());

        return $imageName . '.' . $file->getExtension();
    }

    private function deleteImage($post){
        $fs = Di::get(FileSystem::class);
        
        if ($fs->exists(uploads_dir() . DS . $post['image'])) {
            $fs->remove(uploads_dir() . DS . $post['image']);
        }
    }

}
