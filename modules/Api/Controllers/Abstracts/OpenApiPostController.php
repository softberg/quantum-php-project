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

namespace Modules\Api\Controllers\Abstracts;

use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class OpenApiPostController
 * @package Modules\Api
 */
abstract class OpenApiPostController extends ApiController
{

    /**
     * Get posts action
     * @OA\Get(
     *    path="/api/posts",
     *    tags={"Posts"},
     *    summary="Get posts action",
     *    operationId="getPosts",
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     */
    abstract public function getPosts(Response $response);

    /**
     * Get post action
     * @OA\Get(
     *    path="/api/post/{id}",
     *    tags={"Posts"},
     *    summary="Get post action",
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
     *      response=404,
     *      description="Not Found"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param string|null $lang
     * @param string $postId
     * @param Response $response
     */
    abstract public function getPost(?string $lang, string $postId, Response $response);

    /**
     * Get my posts action
     * @OA\Get(
     *    path="/api/my-posts",
     *    tags={"Posts"},
     *    summary="Get my posts action",
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
     *      response=401,
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     */
    abstract public function getMyPosts(Response $response);

    /**
     * Create post action
     * @OA\Post(
     *  path="/api/my-posts/create",
     *    tags={"Posts"},
     *    summary="Create post action",
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
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function createPost(Request $request, Response $response);

    /**
     * Amend post action
     * @OA\Put(
     *  path="/api/my-posts/amend/{id}",
     *    tags={"Posts"},
     *    summary="Amend post action",
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
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     * @param string|null $lang
     * @param string $postId
     */
    abstract public function amendPost(Request $request, Response $response, ?string $lang, string $postId);

    /**
     * Delete post action
     * @OA\Delete(
     *    path="/api/my-posts/delete/{id}",
     *    tags={"Posts"},
     *    summary="Delete post action",
     *    operationId="postDelete",
     *    security={
     *      {"bearer_token": {}}
     *    },
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
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     * @param string|null $lang
     * @param string $postId
     */
    abstract public function deletePost(Response $response, ?string $lang, string $postId);

    /**
     * Delete post image action
     * @OA\Delete(
     *    path="/api/my-posts/delete-image/{id}",
     *    tags={"Posts"},
     *    summary="Delete post image action",
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
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     * @param string|null $lang
     * @param string $postId
     */
    abstract public function deletePostImage(Response $response, ?string $lang, string $postId);

}
