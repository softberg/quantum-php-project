<?php

namespace Quantum\Tests\Feature\Api;

use Quantum\Tests\Feature\AppTestCase;
use Quantum\Factory\ModelFactory;
use Shared\Models\Post;

class PostControllerTest extends AppTestCase
{
	/**
	 * @var string
	 */
	protected $email = 'test@test.test';

	/**
	 * @var string
	 */
	protected $password = 'password';

	public function setUp(): void
	{
		parent::setUp();
	}

	public function testPostsApi()
	{
		$response = $this->request('get', '/api/en/posts');

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('data', $response->all());
		$this->assertArrayHasKey('id', $response->get('data')[0]);
		$this->assertArrayHasKey('title', $response->get('data')[0]);
		$this->assertArrayHasKey('content', $response->get('data')[0]);
		$this->assertArrayHasKey('image', $response->get('data')[0]);
		$this->assertArrayHasKey('date', $response->get('data')[0]);
		$this->assertArrayHasKey('author', $response->get('data')[0]);
		$this->assertCount(8, $response->get('data'));
		$this->assertArrayHasKey('pagination', $response->all());
		$this->assertArrayHasKey('total_records', $response->get('pagination'));
		$this->assertArrayHasKey('current_page', $response->get('pagination'));
		$this->assertArrayHasKey('next_page', $response->get('pagination'));
		$this->assertArrayHasKey('prev_page', $response->get('pagination'));
		$this->assertEquals(10, $response->get('pagination')['total_records']);
		$this->assertEquals(1, $response->get('pagination')['current_page']);
		$this->assertEquals(2, $response->get('pagination')['next_page']);
		$this->assertEquals(1, $response->get('pagination')['prev_page']);
	}

	public function testSinglePostApi()
	{
		$post = ModelFactory::get(Post::class)->first();

		$response = $this->request('get', '/api/en/post/' . $post->uuid);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('data', $response->all());
		$this->assertArrayHasKey('id', $response->get('data'));
		$this->assertArrayHasKey('title', $response->get('data'));
		$this->assertArrayHasKey('content', $response->get('data'));
		$this->assertArrayHasKey('image', $response->get('data'));
		$this->assertArrayHasKey('date', $response->get('data'));
		$this->assertArrayHasKey('author', $response->get('data'));
	}

	public function testMyPostsApi()
	{
		$tokens = $this->signInAndReturnTokens();

		$response = $this->request('get', '/api/en/my-posts', [], [
			'Authorization' => 'Bearer ' . $tokens['access_token'],
			'refresh_token' => $tokens['refresh_token']
		]);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('data', $response->all());
		$this->assertCount(10, $response->get('data'));
		$this->assertArrayHasKey('id', $response->get('data')[0]);
		$this->assertArrayHasKey('title', $response->get('data')[0]);
		$this->assertArrayHasKey('content', $response->get('data')[0]);
		$this->assertArrayHasKey('image', $response->get('data')[0]);
		$this->assertArrayHasKey('date', $response->get('data')[0]);
		$this->assertArrayHasKey('author', $response->get('data')[0]);
	}

	public function testPostCreateApi()
	{
		$tokens = $this->signInAndReturnTokens();

		$response = $this->request('post', '/api/en/my-posts/create',
			[
				'title' => 'test title',
				'content' => 'test content',
				'image' => '',
				'updated_at' => date('Y-m-d H:i:s'),
			],
			['Authorization' => 'Bearer ' . $tokens['access_token']]
		);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Created successfully', $response->get('message'));
	}

	public function testAmendPostApi()
	{
		$post = ModelFactory::get(Post::class)->first();
		$tokens = $this->signInAndReturnTokens();

		$response = $this->request('put', '/api/en/my-posts/amend/' . $post->uuid,
			[
				'title' => 'test title123',
				'content' => 'test content123',
			],
			['Authorization' => 'Bearer ' . $tokens['access_token']]
		);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Updated successfully', $response->get('message'));
	}

	public function testDeletePostApi()
	{
		$post = ModelFactory::get(Post::class)->first();
		$tokens = $this->signInAndReturnTokens();

		$response = $this->request('delete', '/api/en/my-posts/delete/' . $post->uuid, [], [
			'Authorization' => 'Bearer ' . $tokens['access_token']
		]);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Deleted successfully', $response->get('message'));
	}

	public function testDeleteImagePostApi()
	{
		$post = ModelFactory::get(Post::class)->first();
		$tokens = $this->signInAndReturnTokens();

		$response = $this->request('delete', '/api/en/my-posts/delete-image/' . $post->uuid, [], [
			'Authorization' => 'Bearer ' . $tokens['access_token']
		]);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Deleted successfully', $response->get('message'));
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}
}