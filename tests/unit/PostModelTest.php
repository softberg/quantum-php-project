<?php

use PHPUnit\Framework\TestCase;
use Modules\Main\Services\Post;
use Quantum\Factory\ServiceFactory;

class PostModelTest extends TestCase 
{

    public $post = [];

    public function setUp() {

        $this->post = (new ServiceFactory)->get(Post::class);
    }

    public function testGetPosts() {
        $this->assertInternalType('object', $this->post);
        $this->assertInternalType('array', $this->post->getPosts());
        $this->assertNotEmpty($this->post->getPosts());
    }

    public function testGetSinglePost() {
        $this->assertInternalType('array', $this->post->getPost(1));
        $this->assertArrayHasKey('title', $this->post->getPost(1));
        $this->assertArrayHasKey('content', $this->post->getPost(1));
        $this->assertEquals($this->post->getPost(1)['title'], 'Lorem ipsum dolor sit amet');
    }

    public function testAddNewPost() {
        $this->post->addPost([
            'title' => 'Custom title',
            'content' => 'Custom content'
        ]);

        $this->assertSame(4, count($this->post->getPosts()));
        $this->assertEquals($this->post->getPost(4)['title'], 'Custom title');
        $this->assertEquals($this->post->getPost(4)['content'], 'Custom content');
    }

    public function testUpdatePost() {
        $this->post->updatePost(1, [
            'title' => 'Modified post title',
            'content' => 'Modified post content',
        ]);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $this->post->getPost(1)['title']);
        $this->assertEquals('Modified post title', $this->post->getPost(1)['title']);
        $this->assertEquals('Modified post content', $this->post->getPost(1)['content']);
    }

    public function testDeletePost() {
        $this->post->deletePost(1);

        $this->assertEquals(2, count($this->post->getPosts()));
        $this->assertNull($this->post->getPost(1));
    }

}
