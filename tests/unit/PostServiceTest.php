<?php

use PHPUnit\Framework\TestCase;
use Quantum\Factory\ServiceFactory;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use Quantum\Di\Di;
use Quantum\App;

class PostServiceTest extends TestCase
{
    public $authService;

    public $postService;

    private $initialUser = [
        'email' => 'anonymous@qt.com',
        'password' => '$2y$12$4Y4/1a4308KEiGX/xo6vgO41szJuDHC7KhpG5nknx/xxnLZmvMyGi',
        'firstname' => 'Tom',
        'lastname' => 'Hunter',
        'role' => 'admin',
        'activation_token' => '',
        'remember_token' => '',
        'reset_token' => '',
        'access_token' => '',
        'refresh_token' => '',
        'otp' => '',
        'otp_expires' => '',
        'otp_token' => '',
    ];

    private $initialPosts = [
        [
            'user_id' => 1,
            'title' => 'Walt Disney',
            'content' => 'The way to get started is to quit talking and begin doing.',
            'image' => null,
            'updated_at' => '05/08/2021 23:13',
        ],
        [
            'user_id' => 1,
            'title' => 'James Cameron',
            'content' => 'If you set your goals ridiculously high and it is a failure, you will fail above everyone else success.',
            'image' => null,
            'updated_at' => '05/08/2021 23:13',
        ]
    ];

    public function setUp(): void
    {
        App::loadCoreFunctions(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'Helpers');

        App::setBaseDir(__DIR__ . DS . '_root');

        Di::loadDefinitions();

        $this->authService = (new ServiceFactory)->get(AuthService::class, ['shared' . DS . 'store', 'users']);

        $this->authService->add($this->initialUser);

        $this->postService = (new ServiceFactory)->get(PostService::class, ['shared' . DS . 'store', 'posts']);

        foreach ($this->initialPosts as $post) {
            $this->postService->addPost($post);
        }
    }

    public function tearDown(): void
    {
        $this->authService->deleteTable();
        $this->postService->deleteTable();
    }

    public function testGetPosts()
    {
        $this->assertIsObject($this->postService);
        $this->assertIsArray($this->postService->getPosts()[0]['posts']);
        $this->assertNotEmpty($this->postService->getPosts()[0]['posts']);
        $this->assertCount(2, $this->postService->getPosts()[0]['posts']);
    }

    public function testGetSinglePost()
    {

        $uuid = $this->postService->getPosts()[0]['posts'][0]['uuid'];

        $post = $this->postService->getPost($uuid);

        $this->assertIsArray($post);
        $this->assertArrayHasKey('uuid', $post);
        $this->assertArrayHasKey('user_id', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('content', $post);
        $this->assertEquals('Walt Disney', $post['title']);
        $this->assertEquals('The way to get started is to quit talking and begin doing.', $post['content']);
    }

    public function testAddNewPost()
    {
        $date = date('m/d/Y H:i');

        $post = $this->postService->addPost([
            'user_id' => 1,
            'title' => 'Just another post',
            'content' => 'Content of just another post',
            'updated_at' => $date
        ]);

        $userPosts = $this->postService->getPosts()[0]['posts'];

        $this->assertCount(3, $userPosts);
        $this->assertEquals('Just another post', $this->postService->getPost($post['uuid'])['title']);
        $this->assertEquals('Content of just another post', $this->postService->getPost($post['uuid'])['content']);
        $this->assertEquals($date, $this->postService->getPost($post['uuid'])['updated_at']);
    }

    public function testUpdatePost()
    {
        $date = date('m/d/Y H:i');

        $userPosts = $this->postService->getPosts()[0]['posts'];
        $uuid = $userPosts[0]['uuid'];

        $this->postService->updatePost($uuid, [
            'title' => 'Walt Disney Jr.',
            'content' => 'The best way to get started is to quit talking and begin doing.',
            'image' => 'https://somedomain.com/images/image.jpg',
            'updated_at' => $date
        ]);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $this->postService->getPost($uuid)['title']);
        $this->assertEquals('Walt Disney Jr.', $this->postService->getPost($uuid)['title']);
        $this->assertEquals('The best way to get started is to quit talking and begin doing.', $this->postService->getPost($uuid)['content']);
        $this->assertEquals('https://somedomain.com/images/image.jpg', $this->postService->getPost($uuid)['image']);
        $this->assertEquals($date, $this->postService->getPost($uuid)['updated_at']);
    }

    public function testDeletePost()
    {
        $post = $this->postService->addPost([
            'title' => 'Just another post',
            'content' => 'Content of just another post',
            'updated_at' => date('m/d/Y H:i')
        ]);

        $userPosts = $this->postService->getPosts()[0]['posts'];

        $this->assertCount(3, $userPosts);

        $this->postService->deletePost($post['uuid']);

        $Post = $this->postService->getPosts()[0]['posts'];

        $this->assertCount(2, $Post);

        $this->assertEmpty($this->postService->getPost($post['uuid']));

    }
}
