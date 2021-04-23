<?php

use PHPUnit\Framework\TestCase;
use Base\Services\PostService;
use Quantum\Factory\ServiceFactory;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Loader\Loader;
use Quantum\Di\Di;

class PostServiceTest extends TestCase
{

    public $postService;
    private $initialPosts = [
        1 => [
            'title' => 'Walt Disney',
            'content' => 'The way to get started is to quit talking and begin doing.',
        ],
        [
            'title' => 'James Cameron',
            'content' => 'If you set your goals ridiculously high and it is a failure, you will fail above everyone else success.'
        ]
    ];

    public function setUp(): void
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $loader = new Loader(new FileSystem);

        $loader->loadFile(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'constants.php');

        $loader->loadDir(HELPERS_DIR . DS . 'functions');

        Di::loadDefinitions();

        $reflectionProperty = new \ReflectionProperty(Di::class, 'dependencies');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(Di::class, [
            \Quantum\Loader\Loader::class,
            \Quantum\Libraries\Storage\FileSystem::class,
        ]);

        $this->postService = (new ServiceFactory)->get(PostService::class);

        $reflectionProperty = new \ReflectionProperty($this->postService, 'posts');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->postService, []);

        foreach ($this->initialPosts as $post) {
            $this->postService->addPost($post);
        }
    }

    public function testGetPosts()
    {
        $this->assertIsObject($this->postService);
        $this->assertIsArray($this->postService->getPosts());
        $this->assertNotEmpty($this->postService->getPosts());
        $this->assertCount(2, $this->postService->getPosts());
    }

    public function testGetSinglePost()
    {
        $post = $this->postService->getPost(1);
        $this->assertIsArray($post);
        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('content', $post);
        $this->assertEquals($post['title'], 'Walt Disney');
        $this->assertEquals($post['content'], 'The way to get started is to quit talking and begin doing.');
    }

    public function testAddNewPost()
    {
        $this->postService->addPost([
            'title' => 'Just another post',
            'content' => 'Content of just another post'
        ]);

        $this->assertCount(3, $this->postService->getPosts());
        $this->assertEquals($this->postService->getPost(3)['title'], 'Just another post');
        $this->assertEquals($this->postService->getPost(3)['content'], 'Content of just another post');
    }

    public function testUpdatePost()
    {
        $this->postService->updatePost(1, [
            'title' => 'Walt Disney Jr.',
            'content' => 'The best way to get started is to quit talking and begin doing.',
        ]);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $this->postService->getPost(1)['title']);
        $this->assertEquals('Walt Disney Jr.', $this->postService->getPost(1)['title']);
        $this->assertEquals('The best way to get started is to quit talking and begin doing.', $this->postService->getPost(1)['content']);
    }

    public function testDeletePost()
    {
        $this->postService->addPost([
            'title' => 'Just another post',
            'content' => 'Content of just another post'
        ]);

        $this->assertCount(3, $this->postService->getPosts());
        $this->postService->deletePost(3);
        $this->assertCount(2, $this->postService->getPosts());
        $this->assertNull($this->postService->getPost(3));
    }

}
