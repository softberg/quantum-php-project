<?php

declare(strict_types=1);

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 3.0.0
 */

namespace Shared\Services;

use Quantum\Model\Exceptions\ModelException;
use Shared\Transformers\CommentTransformer;
use Quantum\App\Exceptions\BaseException;
use Quantum\Model\ModelCollection;
use Quantum\Service\Service;
use Shared\DTOs\CommentDTO;
use Shared\Models\Comment;
use Shared\Models\User;

/**
 * Class CommentService
 * @package Shared\Services
 */
class CommentService extends Service
{
    /**
     * @var Comment
     */
    private $model;
    private CommentTransformer $transformer;

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
     * @return ModelCollection
     * @throws BaseException
     * @throws ModelException
     */
    public function getCommentsByPost(string $postUuid): ModelCollection
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
        /** @var Comment $comment */
        $comment = $this->model->criteria('uuid', '=', $uuid)->first();
        return $comment;
    }

    /**
     * Add a new comment
     * @param CommentDTO $commentDto
     * @return array
     * @throws ModelException
     */
    public function addComment(CommentDTO $commentDto): array
    {
        $uuid = uuid_ordered();

        $comment = $this->model->create();
        $comment->fill(array_merge(['uuid' => $uuid], $commentDto->toArray()));
        $comment->save();

        return $comment->asArray();
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
    public function deleteAllComments(): void
    {
        $this->model->truncate();
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
