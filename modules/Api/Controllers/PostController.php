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
 * @since 2.6.0
 */

namespace Modules\Api\Controllers;

use Quantum\Factory\ServiceFactory;
use Shared\Services\PostService;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class PostController
 * @package Modules\Api\Controllers
 */
class PostController extends ApiController
{

    /**
     * Post service
     * @var \Shared\Services\PostService
     */
    public $postService;

    /**
     * Magic __before
     * @param \Quantum\Factory\ServiceFactory $serviceFactory
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
     * @param string $uuid
     * @param \Quantum\Http\Response $response
     */
    public function getPost(string $lang, string $uuid, Response $response)
    {
        if (!$uuid && $lang) {
            $uuid = $lang;
        }

        $post = $this->postService->getPost($uuid);

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
     * Get my posts action
     * @param string $lang
     * @param \Quantum\Http\Response $response
     */
    public function getMyPosts(string $lang, Response $response)
    {
        $user_id = auth()->user()->getFieldValue('id');
        $posts = $this->postService->getMyPosts($user_id);

        if ($posts) {
            $response->json([
                'status' => 'success',
                'data' => $posts
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
     */
    public function createPost(Request $request, Response $response)
    {
        $postData = [
            'user_id' => (int)auth()->user()->getFieldValue('id'),
            'title' => $request->get('title', null, true),
            'content' => $request->get('content', null, true),
            'image' => '',
            'updated_at' => date('Y-m-d H:i:S'),
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
     * @param string $uuid
     */
    public function amendPost(Request $request, Response $response, string $uuid)
    {
        $postData = [
            'title' => $request->get('title', null, true),
            'content' => $request->get('content', null, true),
            'updated_at' => date('Y-m-d H:i:S'),
        ];

        $post = $this->postService->getPost($uuid);

        if (!empty($post) && $post['user_id'] == auth()->user()->getFieldValue('id')){
            if ($request->hasFile('image')) {
                if ($post['image']) {
                    $this->postService->deleteImage($post['image']);
                }

                $imageName = $this->postService->saveImage($request->getFile('image'), slugify($request->get('title')));
                $postData['image'] = base_url() . '/uploads/' . $imageName;
            }

            $this->postService->updatePost($uuid, $postData);
            $response->json([
                'status' => 'success',
                'message' => t('common.updated_successfully')
            ]);
        } else{
            $response->json([
                'status' => 'error',
                'message' => t('common.post_not_found')
            ]);

            stop();
        }

    }

    /**
     * Delete post action
     * @param \Quantum\Http\Response $response
     * @param string $uuid
     */
    public function deletePost(Response $response, string $uuid)
    {
        $post = $this->postService->getPost($uuid);

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

        if ($this->postService->deletePost($uuid)) {
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
     * @param string $uuid
     */
    public function deletePostImage(Response $response, string $uuid)
    {
        $post = $this->postService->getPost($uuid);

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

        $post['image'] = '';
        $this->postService->updatePost($uuid, $post);

        $response->json([
            'status' => 'success',
            'message' => t('common.deleted_successfully')
        ]);
    }

}
