<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Model\Factories\ModelFactory;
use Quantum\Tests\Feature\AppTestCase;
use Quantum\Http\Request;
use Shared\Models\Post;

class PostManagementControllerTest extends AppTestCase
{

    private $tokens = [];

	public function setUp(): void
	{
		parent::setUp();

        $this->tokens = $this->signInAndGetTokens();

        Request::flush();
	}

    public function tearDown(): void
    {
        parent::tearDown();
    }

	public function testModuleApiMyPostsEndpoint()
	{
        $method = 'GET';
        $endpoint = '/api/my-posts';
        $body = [];
        $headers = [
            'Authorization' => 'Bearer ' . $this->tokens['access_token'],
            'refresh_token' => $this->tokens['refresh_token']
        ];

		$response = $this->request($method, $endpoint, $body, $headers);

		$this->assertIsObject($response);

		$this->assertEquals('success', $response->get('status'));

		$this->assertArrayHasKey('data', $response->all());

        $postData = $response->get('data');

		$this->assertCount(10, $postData);

        $firstPost = $postData[0];

        $this->assertIsArray($firstPost);

		$this->assertArrayHasKey('uuid', $firstPost);
		$this->assertArrayHasKey('title', $firstPost);
		$this->assertArrayHasKey('content', $firstPost);
		$this->assertArrayHasKey('image', $firstPost);
		$this->assertArrayHasKey('date', $firstPost);
		$this->assertArrayHasKey('author', $firstPost);
	}

	public function testModuleApiPostCreateEndpoint()
	{
        $method = 'POST';
        $endpoint = '/api/my-posts/create';
        $body = [
            'title' => 'test title',
            'content' => 'test content',
            'image' => '',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $headers = ['Authorization' => 'Bearer ' . $this->tokens['access_token']];

		$response = $this->request($method, $endpoint, $body, $headers);

		$this->assertIsObject($response);

        $this->assertEquals('success', $response->get('status'));

        $this->assertEquals('Created successfully', $response->get('message'));

        $post = $response->get('data');

        $this->assertIsArray($post);

        $this->assertArrayHasKey('uuid', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('content', $post);
        $this->assertArrayHasKey('image', $post);
        $this->assertArrayHasKey('date', $post);
        $this->assertArrayHasKey('author', $post);

    }

	public function testModuleApiAmendPostEndpoint()
	{
        $method = 'PUT';
        $endpoint = '/api/my-posts/amend/';
        $body = [
            'title' => 'test title123',
            'content' => 'test content123',
        ];
        $headers = ['Authorization' => 'Bearer ' . $this->tokens['access_token']];

		$post = ModelFactory::get(Post::class)->first();

		$response = $this->request($method, $endpoint . $post->uuid, $body, $headers);

		$this->assertIsObject($response);

		$this->assertEquals('success', $response->get('status'));

		$this->assertEquals('Updated successfully', $response->get('message'));

        $post = $response->get('data');

        $this->assertIsArray($post);

        $this->assertArrayHasKey('uuid', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('content', $post);
        $this->assertArrayHasKey('image', $post);
        $this->assertArrayHasKey('date', $post);
        $this->assertArrayHasKey('author', $post);
	}

	public function testModuleApiDeletePostEndpoint()
	{
        $method = 'DELETE';
        $endpoint = '/api/my-posts/delete/';
        $body = [];
        $headers = ['Authorization' => 'Bearer ' . $this->tokens['access_token']];

		$post = ModelFactory::get(Post::class)->first();

		$response = $this->request($method, $endpoint . $post->uuid, $body, $headers);

		$this->assertIsObject($response);

		$this->assertEquals('success', $response->get('status'));

		$this->assertEquals('Deleted successfully', $response->get('message'));
	}

	public function testModuleApiDeletePostImageEndpoint()
	{
        $method = 'DELETE';
        $endpoint = '/api/my-posts/delete-image/';
        $body = [];
        $headers = ['Authorization' => 'Bearer ' . $this->tokens['access_token']];

		$post = ModelFactory::get(Post::class)->first();

		$response = $this->request($method, $endpoint . $post->uuid, $body, $headers);

		$this->assertIsObject($response);

		$this->assertEquals('success', $response->get('status'));

		$this->assertEquals('Deleted successfully', $response->get('message'));
	}
}