<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Model\Factories\ModelFactory;
use Quantum\Tests\Feature\AppTestCase;
use Shared\Models\Comment;
use Quantum\Http\Response;
use Quantum\Http\Request;

class CommentControllerTest extends AppTestCase
{
    private array $tokens = [];

    private $post = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->tokens = $this->signInAndGetTokens();

        $response = $this->request('get', '/api/posts');

        $postData = $response->get('data');

        $this->post = $postData[0];

        Request::flush();
        Response::flush();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testModuleApiCommentCreateEndpoint()
    {
        $method = 'POST';
        $endpoint = '/api/comments/create/';
        $body = ['content' => 'My first comment'];
        $headers = ['Authorization' => 'Bearer ' . $this->tokens['access_token']];

        $response = $this->request($method, $endpoint . $this->post['uuid'], $body, $headers);

        $this->assertEquals('success', $response->get('status'));

        $this->assertEquals('Created successfully', $response->get('message'));

        $comment = $response->get('data');

        $this->assertIsArray($comment);

        $this->assertArrayHasKey('uuid', $comment);
        $this->assertArrayHasKey('user_uuid', $comment);
        $this->assertArrayHasKey('post_uuid', $comment);
        $this->assertArrayHasKey('content', $comment);

        ModelFactory::get(Comment::class)->findOneBy('uuid', $comment['uuid'])->delete();
    }

    public function testModuleApiCommentDeleteEndpoint()
    {
        $method = 'POST';
        $endpoint = '/api/comments/create/';
        $body = ['content' => 'Comment to be deleted'];
        $headers = ['Authorization' => 'Bearer ' . $this->tokens['access_token']];

        $response = $this->request($method, $endpoint . $this->post['uuid'], $body, $headers);

        $comment = $response->get('data');

        $method = 'DELETE';
        $endpoint = '/api/comments/delete/';
        $body = [];

        $response = $this->request($method, $endpoint . $comment['uuid'], $body, $headers);

        $this->assertIsObject($response);

        $this->assertEquals('success', $response->get('status'));

        $this->assertEquals('Deleted successfully', $response->get('message'));
    }
}
