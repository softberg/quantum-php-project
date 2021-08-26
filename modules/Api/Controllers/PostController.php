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

namespace Modules\Api\Controllers;

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Factory\ServiceFactory;
use Quantum\Libraries\Upload\File;
use Base\Services\PostService;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Quantum\Di\Di;

/**
 * Class PostController
 * @package Modules\Api\Controllers
 */
class PostController extends ApiController
{

    /**
     * Post service
     * @var \Base\Services\PostService
     */
    public $postService;

    /**
     * Magic __before
     * @param \Quantum\Factory\ServiceFactory $serviceFactory
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\ServiceException
     * @throws \ReflectionException
     */
    public function __before(ServiceFactory $serviceFactory)
    {
        $this->postService = $serviceFactory->get(PostService::class);
    }

    /**
     * Get posts action
     * @param \Quantum\Http\Response $response
     */
    public function getPosts(Response $response)
    {
        $posts = $this->postService->getPosts();
        $response->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    /**
     * Get post action
     * @param string $lang
     * @param int $id
     * @param \Quantum\Http\Response $response
     */
    public function getPost(string $lang, int $id, Response $response)
    {
        if (!$id && $lang) {
            $id = $lang;
        }

        $post = $this->postService->getPost($id);

        if ($post) {
            $response->json([
                'status' => 'success',
                'data' => $post
            ]);
        } else {
            $response->json([
                'status' => 'error',
                'message' => t('common.post_not_found')
            ]);
        }
    }

    /**
     * Create post action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \Gumlet\ImageResizeException
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\FileUploadException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function createPost(Request $request, Response $response)
    {
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
        $response->json([
            'status' => 'success',
            'message' => t('common.created_successfully')
        ]);
    }

    /**
     * Amend post action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param int $id
     * @throws \Gumlet\ImageResizeException
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\FileUploadException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \Quantum\Exceptions\StopExecutionException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function amendPost(Request $request, Response $response, int $id)
    {
        $postData = [
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'author' => auth()->user()->getFieldValue('email'),
            'updated_at' => date('m/d/Y H:i'),
        ];

        $post = $this->postService->getPost($id);

        if (!$post) {
            $response->json([
                'status' => 'error',
                'message' => t('common.post_not_found')
            ]);

            stop();
        }

        if ($request->hasFile('image')) {
            if ($post['image']) {
                $this->postService->deleteImage($post['image']);
            }

            $imageName = $this->postService->saveImage($request->getFile('image'), slugify($request->get('title')));
            $postData['image'] = base_url() . '/uploads/' . $imageName;
        }

        $this->postService->updatePost($id, $postData);
        $response->json([
            'status' => 'success',
            'message' => t('common.updated_successfully')
        ]);
    }

    /**
     * Delete post action
     * @param \Quantum\Http\Response $response
     * @param int $id
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\StopExecutionException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function deletePost(Response $response, int $id)
    {
        $post = $this->postService->getPost($id);

        if (!$post) {
            $response->json([
                'status' => 'error',
                'message' => t('common.post_not_found')
            ]);

            stop();
        }

        if ($post['image']) {
            $this->postService->deleteImage($post['image']);
        }

        if ($this->postService->deletePost($id)) {
            $response->json([
                'status' => 'success',
                'message' => t('common.deleted_successfully')
            ]);
        } else {
            $response->json([
                'status' => 'error',
                'message' => t('common.post_not_found')
            ]);
        }
    }

    /**
     * Delete post image action
     * @param \Quantum\Http\Response $response
     * @param int $id
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\StopExecutionException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function deletePostImage(Response $response, int $id)
    {
        $post = $this->postService->getPost($id);

        if (!$post) {
            $response->json([
                'status' => 'error',
                'message' => t('common.post_not_found')
            ]);

            stop();
        }

        if ($post['image']) {
            $this->postService->deleteImage($post['image']);
        }

        $post['image'] = null;
        $this->postService->updatePost($id, $post);

        $response->json([
            'status' => 'success',
            'message' => t('common.deleted_successfully')
        ]);
    }

}
