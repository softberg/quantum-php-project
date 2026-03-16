<?php

namespace Quantum\Tests\Unit\shared\Services;

use Quantum\Model\Factories\ModelFactory;
use Quantum\Service\Factories\ServiceFactory;
use Shared\Models\Comment;
use Shared\Services\CommentService;
use Quantum\Model\ModelCollection;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use PHPUnit\Framework\TestCase;

class CommentServiceTest extends TestCase
{

    protected $commentService;

    protected $postService;

    protected $authService;

    public function setUp(): void
    {
        parent::setUp();

        $this->authService = ServiceFactory::create(AuthService::class);
        $this->postService = ServiceFactory::create(PostService::class);
        $this->commentService = ServiceFactory::create(CommentService::class);
    }

    public function testCommentServiceGetCommentsByPost()
    {
        $post = $this->postService->getPosts()->first();

        $rawComments = ModelFactory::get(Comment::class)->withTrashed()->criteria('post_uuid', '=', $post->uuid)->get();
        $allRaw = ModelFactory::get(Comment::class)->withTrashed()->get();
        $user = $this->authService->getAll()->first();

        fwrite(STDERR, sprintf(
            "\n[DIAG] post_uuid=%s, user_uuid=%s, raw_for_post=%d, total_raw=%d\n",
            $post->uuid,
            $user->uuid,
            count($rawComments),
            count($allRaw)
        ));

        if (count($allRaw) > 0) {
            $first = $allRaw->first();
            fwrite(STDERR, sprintf(
                "[DIAG] first_comment: post_uuid=%s, user_uuid=%s, deleted_at=%s\n",
                $first->prop('post_uuid') ?? 'NULL',
                $first->prop('user_uuid') ?? 'NULL',
                $first->prop('deleted_at') ?? 'NULL'
            ));
        }

        $comments = $this->commentService->getCommentsByPost($post->uuid);

        $this->assertInstanceOf(ModelCollection::class, $comments);
        $this->assertCount(3, $comments);
    }

    public function testCommentServiceGetComment()
    {
        $paginatedPosts = $this->postService->getPosts(5, 1);
        $post = $paginatedPosts->data()->first();

        $comments = $this->commentService->getCommentsByPost($post->uuid);
        $comment = $comments->first();

        $singleComment = $this->commentService->getComment($comment->uuid);

        $commentData = $singleComment->asArray();

        $this->assertArrayHasKey('content', $commentData);

        $this->assertArrayHasKey('post_uuid', $commentData);

        $this->assertArrayHasKey('user_uuid', $commentData);

        $this->assertArrayHasKey('created_at', $commentData);
    }

    public function testCommentServiceAddComment()
    {
        $post = $this->postService->getPosts()->first();
        $user = $this->authService->getAll()->first();

        $data = $this->commentService->addComment([
            'user_uuid' => $user->uuid,
            'post_uuid' => $post->uuid,
            'content'   => 'New comment content',
        ]);

        $this->assertArrayHasKey('uuid', $data);

        $comment = $this->commentService->getComment($data['uuid']);
        $this->assertEquals('New comment content', $comment->content);

        $this->commentService->deleteComment($data['uuid']);
    }

    public function testCommentServiceDeleteComment()
    {
        $post = $this->postService->getPosts()->first();
        $user = $this->authService->getAll()->first();

        $data = $this->commentService->addComment([
            'user_uuid' => $user->uuid,
            'post_uuid' => $post->uuid,
            'content' => 'To be deleted'
        ]);

        $uuid = $data['uuid'];

        $this->assertCount(
            4,
            $this->commentService->getCommentsByPost($post->uuid)
        );

        $this->assertTrue($this->commentService->deleteComment($uuid));

        $this->assertCount(
            3,
            $this->commentService->getCommentsByPost($post->uuid)
        );

        $comment = $this->commentService->getComment($uuid);

        $this->assertTrue($comment->isEmpty());
    }
}