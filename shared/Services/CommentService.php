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
 * @since 2.9.9
 */

namespace Shared\Services;

use Quantum\Model\Exceptions\ModelException;
use Shared\Transformers\CommentTransformer;
use Quantum\App\Exceptions\BaseException;
use Quantum\Service\QtService;
use Shared\Models\Comment;
use Shared\Models\User;

/**
 * Class CommentService
 * @package Shared\Services
 */
class CommentService extends QtService
{

    /**
     * @var Comment
     */
    private $model;

    /**
     * @var CommentTransformer
     */
    private $transformer;

    /**
     * @param CommentTransformer $transformer
     * @throws ModelException
     * @throws BaseException
     */
    public function __construct(CommentTransformer $transformer)
    {
        $this->model = model(Comment::class);
        $this->transformer = $transformer;
    }

    /**
     * Get comments by post
     * @param string $postUuid
     * @return mixed
     * @throws BaseException
     * @throws ModelException
     */
    public function getCommentsByPost(string $postUuid)
    {
        return $this->model
            ->joinTo(model(User::class))
            ->criteria('post_uuid', '=', $postUuid)
            ->select(
                'comments.uuid',
                'comments.content',
                'comments.created_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.image' => 'image'],
                ['users.uuid' => 'user_directory']
            )
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get comment
     * @param string $uuid
     * @return Comment
     */
    public function getComment(string $uuid): Comment
    {
        return $this->model->criteria('uuid', '=', $uuid)->first();
    }

    /**
     * Add a new comment
     * @param array $data
     * @return array
     * @throws ModelException
     */
    public function addComment(array $data): array
    {
        $data['uuid'] = $data['uuid'] ?? uuid_ordered();
        $data['created_at'] = date('Y-m-d H:i:s');

        $comment = $this->model->create();
        $comment->fillObjectProps($data);
        $comment->save();

        return $data;
    }

    /**
     * Delete a comment
     * @param string $uuid
     * @return bool
     */
    public function deleteComment(string $uuid): bool
    {
        return $this->model->findOneBy('uuid', $uuid)->delete();
    }

    /**
     * Delete all comments
     * @throws ModelException
     */
    public function deleteAllComments()
    {
        $this->model->deleteTable();
    }

    /**
     * Transform data
     * @param array $comments
     * @return array
     */
    public function transformData(array $comments): array
    {
        return transform($comments, $this->transformer);
    }
}