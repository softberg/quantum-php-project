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
 * @since 2.8.0
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
     * Works before an action
     */
    public function __before()
    {
        $this->postService = ServiceFactory::get(PostService::class);
    }

    /**
     *  Get posts action
     *  @OA\Get(
     *    path="/api/posts",
     *    tags={"Posts"},
     *    summary="This api returns all posts",
     *    operationId="getPosts",
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Not Found"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     *  @param Response $response
     */
    public function getPosts(Response $response)
    {
        $response->json([
            'status' => 'success',
            'data' => $this->postService->getPosts()
        ]);
    }

    /**
     *  Get post action
     *  @OA\Get(
     *    path="/api/post/{id}",
     *    tags={"Posts"},
     *    summary="This api returns all posts",
     *    operationId="getPost",
     *    @OA\Parameter(
     *      name="id",
     *      description="Post Id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Not Found"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     *  @param string|null $lang
     *  @param string $postId
     *  @param Response $response
     */
    public function getPost(?string $lang, string $postId, Response $response)
    {
        $response->json([
            'status' => 'success',
            'data' => $this->postService->getPost($postId)
        ]);
    }

    /**
     *  Get my posts action
     *  @OA\Get(
     *    path="/api/my-posts",
     *    tags={"Posts"},
     *    summary="This api returns my posts",
     *    operationId="getMyPosts",
     *    security={
     *      {"bearer_token": {}}
     *    },
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Not Found"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     *  @param Response $response
     */
    public function getMyPosts(Response $response)
    {
        $response->json([
            'status' => 'success',
            'data' => $this->postService->getMyPosts((int) auth()->user()->id)
        ]);
    }

    /**
     *  Create post action
     *  @OA\Post(
     *  path="/api/my-posts/create",
     *    tags={"Posts"},
     *    summary="Create post",
     *    operationId="postCreate",
     *    security={
     *      {"bearer_token": {}
     *    }},
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *        mediaType="multipart/form-data",
     *          @OA\Schema(
     *            type="object",
     *            required={"title", "content"},
     *            @OA\Property(
     *              property="title",
     *              type="string",
     *            ),
     *            @OA\Property(
     *              property="content",
     *              type="string",
     *            ),
     *            @OA\Property(
     *              property="image",
     *              type="file",
     *            )
     *          )
     *        )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Request $request
     *  @param Response $response
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
            $postData['image'] = base_url() . '/uploads/' . $imageName;
        }

        $this->postService->addPost($postData);

        $response->json([
            'status' => 'success',
            'message' => t('common.created_successfully')
        ]);
    }

    /**
     *  Amend post action
     *  @OA\Put(
     *  path="/api/my-posts/amend/{id}",
     *    tags={"Posts"},
     *    summary="Edit post",
     *    operationId="postEdit",
     *    security={
     *      {"bearer_token": {}
     *    }},
     *    @OA\Parameter(
     *      name="id",
     *      description="Post id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(
     *          type="object",
     *          required={"title", "content"},
     *            @OA\Property(
     *              property="title",
     *              type="string",
     *            ),
     *            @OA\Property(
     *              property="content",
     *              type="string",
     *            ),
     *            @OA\Property(
     *              property="image",
     *              type="file",
     *            )
     *         )
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Request $request
     *  @param Response $response
     *  @param string|null $lang
     *  @param string $postId
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
            $postData['image'] = base_url() . '/uploads/' . $imageName;
        }

        $this->postService->updatePost($postId, $postData);

        $response->json([
            'status' => 'success',
            'message' => t('common.updated_successfully')
        ]);
    }

    /**
     *  Delete post action
     *  @OA\Delete(
     *    path="/api/my-posts/delete/{id}",
     *    tags={"Posts"},
     *    summary="Delete post",
     *    operationId="postDelete",
     *    security={
     *      {"bearer_token": {}
     *    }},
     *    @OA\Parameter(
     *      name="id",
     *      description="Post id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Response $response
     *  @param string|null $lang
     *  @param string $postId
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
     *  Delete post image action
     *  @OA\Delete(
     *    path="/api/my-posts/delete-image/{id}",
     *    tags={"Posts"},
     *    summary="Delete post image",
     *    operationId="postImageDelete",
     *    security={
     *      {"bearer_token": {}
     *    }},
     *    @OA\Parameter(
     *      name="id",
     *      description="Post id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Response $response
     *  @param string|null $lang
     *  @param string $postId
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
