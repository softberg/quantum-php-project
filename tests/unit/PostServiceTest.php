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
    private $initialPost = [
        'title' => 'Lorem ipsum dolor sit amet',
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean condimentum condimentum nibh.',
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

        $this->postService->addPost($this->initialPost);
    }

    public function testGetPosts()
    {
        $this->assertIsObject($this->postService);
        $this->assertIsArray($this->postService->getPosts());
        $this->assertNotEmpty($this->postService->getPosts());
        $this->assertSame(1, count($this->postService->getPosts()));
    }

    public function testGetSinglePost()
    {
        $post = $this->postService->getPost(1);
        $this->assertIsArray($post);
        $this->assertArrayHasKey('content', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertEquals($post['title'], 'Lorem ipsum dolor sit amet');
    }

    public function testAddNewPost()
    {
        $this->postService->addPost([
            'title' => 'Vestibulum lacus purus',
            'content' => 'Vestibulum lacus purus, bibendum non nunc ac, fermentum eleifend augue.'
        ]);

        $this->assertSame(2, count($this->postService->getPosts()));
        $this->assertEquals($this->postService->getPost(2)['title'], 'Vestibulum lacus purus');
        $this->assertEquals($this->postService->getPost(2)['content'], 'Vestibulum lacus purus, bibendum non nunc ac, fermentum eleifend augue.');
    }

    public function testUpdatePost()
    {
        $this->postService->updatePost(1, [
            'title' => 'Modified post title',
            'content' => 'Modified post content',
        ]);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $this->postService->getPost(1)['title']);
        $this->assertEquals('Modified post title', $this->postService->getPost(1)['title']);
        $this->assertEquals('Modified post content', $this->postService->getPost(1)['content']);
    }

    public function testDeletePost()
    {
        $this->postService->deletePost(1);

        $this->assertEquals(0, count($this->postService->getPosts()));
        $this->assertNull($this->postService->getPost(1));
    }

}
