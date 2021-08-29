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
    private $postRepository = BASE_DIR . DS . 'base' . DS . 'repositories' . DS . 'posts.php';
    private $initialPosts = [
        [
            'id' => 1,
            'title' => 'Walt Disney',
            'content' => 'The way to get started is to quit talking and begin doing.',
            'author' => 'admin@qt.com',
            'image' => null,
            'updated_at' => '05/08/2021 23:13',
        ],
        [
            'id' => 2,
            'title' => 'James Cameron',
            'content' => 'If you set your goals ridiculously high and it is a failure, you will fail above everyone else success.',
            'author' => 'admin@qt.com',
            'image' => null,
            'updated_at' => '05/08/2021 23:13',
        ]
    ];


    public function setUp(): void
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $fs = new FileSystem();

        $loader = new Loader($fs);

        $loader->loadFile(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'constants.php');

        $loader->loadDir(HELPERS_DIR . DS . 'functions');

        $loader->loadDir(BASE_DIR . DS . 'helpers');

        Di::loadDefinitions();

        if($fs->exists($this->postRepository)) {
            $fs->remove($this->postRepository);
        }

        if(!$fs->exists($this->postRepository)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . export([]) . ';';
            $fs->put($this->postRepository, $content);
        }

        $this->postService = (new ServiceFactory)->get(PostService::class);

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
        $this->assertEquals($post['author'], 'admin@qt.com');
    }

    public function testAddNewPost()
    {
        $date = date('m/d/Y H:i');

        $this->postService->addPost([
            'title' => 'Just another post',
            'content' => 'Content of just another post',
            'author' => 'james@mail.com',
            'updated_at' => $date
        ]);

        $this->assertCount(3, $this->postService->getPosts());
        $this->assertEquals('Just another post', $this->postService->getPost(3)['title']);
        $this->assertEquals('Content of just another post', $this->postService->getPost(3)['content']);
        $this->assertEquals('james@mail.com', $this->postService->getPost(3)['author']);
        $this->assertEquals($date, $this->postService->getPost(3)['updated_at']);
    }

    public function testUpdatePost()
    {
        $date = date('m/d/Y H:i');

        $this->postService->updatePost(1, [
            'title' => 'Walt Disney Jr.',
            'content' => 'The best way to get started is to quit talking and begin doing.',
            'author' => 'james@mail.com',
            'image' => 'https://somedomain.com/images/image.jpg',
            'updated_at' => $date
        ]);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $this->postService->getPost(1)['title']);
        $this->assertEquals('Walt Disney Jr.', $this->postService->getPost(1)['title']);
        $this->assertEquals('The best way to get started is to quit talking and begin doing.', $this->postService->getPost(1)['content']);
        $this->assertEquals('james@mail.com', $this->postService->getPost(1)['author']);
        $this->assertEquals('https://somedomain.com/images/image.jpg', $this->postService->getPost(1)['image']);
        $this->assertEquals($date, $this->postService->getPost(1)['updated_at']);
    }

    public function testDeletePost()
    {
        $this->postService->addPost([
            'title' => 'Just another post',
            'content' => 'Content of just another post',
            'author' => 'james@mail.com',
            'updated_at' => date('m/d/Y H:i')
        ]);

        $this->assertCount(3, $this->postService->getPosts());
        $this->postService->deletePost(3);
        $this->assertCount(2, $this->postService->getPosts());
        $this->assertNull($this->postService->getPost(3));
    }

}
