<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Model\Factories\ModelFactory;
use Quantum\Tests\Feature\AppTestCase;
use Quantum\Http\Request;
use Shared\Models\Post;

class PostControllerTest extends AppTestCase
{

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
        $method = 'GET';
        $endpoint = '/api/posts';

		$response = $this->request($method, $endpoint);

		$this->assertIsObject($response);

		$this->assertEquals('success', $response->get('status'));

        $this->assertArrayHasKey('data', $response->all());

        $postData = $response->get('data');

        $this->assertIsArray($postData);

        $this->assertCount(8, $postData);

        $firstPost = $postData[0];

        $this->assertIsArray($firstPost);

        $this->assertArrayHasKey('uuid', $firstPost);
        $this->assertArrayHasKey('title', $firstPost);
        $this->assertArrayHasKey('content', $firstPost);
        $this->assertArrayHasKey('image', $firstPost);
        $this->assertArrayHasKey('date', $firstPost);
        $this->assertArrayHasKey('author', $firstPost);

		$this->assertArrayHasKey('pagination', $response->all());

        $pagination = $response->get('pagination');

        $this->assertIsArray($pagination);

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
        $method = 'GET';
        $endpoint = '/api/post/';

		$post = ModelFactory::get(Post::class)->first();

		$response = $this->request($method, $endpoint . $post->uuid);

		$this->assertIsObject($response);

		$this->assertEquals('success', $response->get('status'));

        $this->assertArrayHasKey('data', $response->all());

        $post = $response->get('data');

        $this->assertIsArray($post);

		$this->assertArrayHasKey('uuid', $post);
		$this->assertArrayHasKey('title', $post);
		$this->assertArrayHasKey('content', $post);
		$this->assertArrayHasKey('image', $post);
		$this->assertArrayHasKey('date', $post);
		$this->assertArrayHasKey('author', $post);
		$this->assertArrayHasKey('comments', $post);

        $firstComment = $post['comments'][0];

        $this->assertIsArray($firstComment);

        $this->assertArrayHasKey('uuid', $firstComment);
        $this->assertArrayHasKey('author', $firstComment);
        $this->assertArrayHasKey('author', $firstComment);
        $this->assertArrayHasKey('content', $firstComment);
        $this->assertArrayHasKey('date', $firstComment);
	}
}