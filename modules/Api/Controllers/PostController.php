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
 * @since 2.9.0
 */

namespace Modules\Api\Controllers;

use Modules\Api\Controllers\Abstracts\OpenApiPostController;
use Quantum\Factory\ServiceFactory;
use Shared\Services\PostService;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class PostController
 * @package Modules\Api
 */
class PostController extends OpenApiPostController
{

    /**
     * Post service
     * @var PostService
     */
    public $postService;

    /**
     * Works before an action
     */
    public function __before()
    {
        $this->postService = ServiceFactory::get(PostService::class);
    }

    /**
     * @inheritDoc
     */
    public function getPosts(Response $response)
    {
        $response->json([
            'status' => 'success',
            'data' => $this->postService->getPosts()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getPost(?string $lang, string $postId, Response $response)
    {
        $response->json([
            'status' => 'success',
            'data' => $this->postService->getPost($postId)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getMyPosts(Response $response)
    {
        $response->json([
            'status' => 'success',
            'data' => $this->postService->getMyPosts((int) auth()->user()->id)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function createPost(Request $request, Response $response)
    {
        $postData = [
            'user_id' => (int) auth()->user()->id,
            'title' => $request->get('title', null, true),
            'content' => $request->get('content', null, true),
            'image' => '',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($request->hasFile('image')) {
            $imageName = $this->postService->saveImage($request->getFile('image'), slugify($request->get('title')));
                $postData['image'] = base_url() . '/uploads/' . auth()->user()->uuid . '/' . $imageName;
        }

        $this->postService->addPost($postData);

        $response->json([
            'status' => 'success',
            'message' => t('common.created_successfully')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function amendPost(Request $request, Response $response, ?string $lang, string $postId)
    {
        $postData = [
            'title' => $request->get('title', null, true),
            'content' => $request->get('content', null, true),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $post = $this->postService->getPost($postId);

        if ($request->hasFile('image')) {
            if ($post['image']) {
                $this->postService->deleteImage($post['image']);
            }

            $imageName = $this->postService->saveImage($request->getFile('image'), slugify($request->get('title')));
            $postData['image'] = base_url() . '/uploads/' . auth()->user()->uuid . '/' . $imageName;
        }

        $this->postService->updatePost($postId, $postData);

        $response->json([
            'status' => 'success',
            'message' => t('common.updated_successfully')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function deletePost(Response $response, ?string $lang, string $postId)
    {
        $post = $this->postService->getPost($postId);

        if ($post['image']) {
            $this->postService->deleteImage($post['image']);
        }

        $this->postService->deletePost($postId);

        $response->json([
            'status' => 'success',
            'message' => t('common.deleted_successfully')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function deletePostImage(Response $response, ?string $lang, string $postId)
    {
        $post = $this->postService->getPost($postId, false);

        if ($post['image']) {
            $this->postService->deleteImage($post['image']);
        }

        $this->postService->updatePost($postId, [
            'title' => $post['title'],
            'content' => $post['content'],
            'image' => '',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $response->json([
            'status' => 'success',
            'message' => t('common.deleted_successfully')
        ]);
    }
}
