<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Model\Factories\ModelFactory;
use Quantum\Tests\Feature\AppTestCase;
use Quantum\Http\Request;
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

        Request::flush();
	}

    public function tearDown(): void
    {
        parent::tearDown();
    }

	public function testModuleApiPostsEndpoint()
	{
		$response = $this->request('get', '/api/en/posts');

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));

        $this->assertArrayHasKey('data', $response->all());

        $postData = $response->get('data');

        $this->assertCount(8, $postData);

        $this->assertArrayHasKey('uuid', $postData[0]);
        $this->assertArrayHasKey('title', $postData[0]);
        $this->assertArrayHasKey('content', $postData[0]);
        $this->assertArrayHasKey('image', $postData[0]);
        $this->assertArrayHasKey('date', $postData[0]);
        $this->assertArrayHasKey('author', $postData[0]);

		$this->assertArrayHasKey('pagination', $response->all());

        $pagination = $response->get('pagination');

		$this->assertArrayHasKey('total_records', $pagination);
		$this->assertArrayHasKey('current_page', $pagination);
		$this->assertArrayHasKey('next_page', $pagination);
		$this->assertArrayHasKey('prev_page', $pagination);

        $this->assertEquals(10, $pagination['total_records']);
		$this->assertEquals(1, $pagination['current_page']);
		$this->assertEquals(2, $pagination['next_page']);
		$this->assertEquals(1,$pagination['prev_page']);
	}

	public function testModuleApiSinglePostEndpoint()
	{
		$post = ModelFactory::get(Post::class)->first();

		$response = $this->request('get', '/api/en/post/' . $post->uuid);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('data', $response->all());

        $rawData = $response->get('data')->getValue();

		$this->assertArrayHasKey('uuid', $rawData);
		$this->assertArrayHasKey('title', $rawData);
		$this->assertArrayHasKey('content', $rawData);
		$this->assertArrayHasKey('image', $rawData);
		$this->assertArrayHasKey('date', $rawData);
		$this->assertArrayHasKey('author', $rawData);
	}

	public function testModuleApiMyPostsEndpoint()
	{
		$tokens = $this->signInAndGetTokens();

		$response = $this->request('get', '/api/en/my-posts', [], [
			'Authorization' => 'Bearer ' . $tokens['access_token'],
			'refresh_token' => $tokens['refresh_token']
		]);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('data', $response->all());

        $postData = $response->get('data');

		$this->assertCount(10, $postData);

		$this->assertArrayHasKey('uuid', $postData[0]);
		$this->assertArrayHasKey('title', $postData[0]);
		$this->assertArrayHasKey('content', $postData[0]);
		$this->assertArrayHasKey('image', $postData[0]);
		$this->assertArrayHasKey('date', $postData[0]);
		$this->assertArrayHasKey('author', $postData[0]);
	}

	public function testModuleApiPostCreateEndpoint()
	{
		$tokens = $this->signInAndGetTokens();

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

	public function testModuleApiAmendPostEndpoint()
	{
		$post = ModelFactory::get(Post::class)->first();

		$tokens = $this->signInAndGetTokens();

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

		$tokens = $this->signInAndGetTokens();

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

		$tokens = $this->signInAndGetTokens();

		$response = $this->request('delete', '/api/en/my-posts/delete-image/' . $post->uuid, [], [
			'Authorization' => 'Bearer ' . $tokens['access_token']
		]);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Deleted successfully', $response->get('message'));
	}
}