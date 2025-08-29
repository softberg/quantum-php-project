<?php

namespace Quantum\Tests\Unit\shared\Services;

use Quantum\Libraries\Storage\UploadedFile;
use Quantum\Tests\Unit\AppTestCase;
use Quantum\Model\ModelCollection;
use Quantum\Paginator\Paginator;
use Shared\Models\Post;

class PostServiceTest extends AppTestCase
{
    const PER_PAGE = 10;

    const CURRENT_PAGE = 1;

    private $initialPosts;

    public function setUp(): void
    {
        parent::setUp();

        $this->initialPosts = $this->postService
            ->getPosts(self::PER_PAGE, self::CURRENT_PAGE)
            ->data()
            ->all();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testPostServiceGetAllPosts()
    {
        $this->assertIsObject($this->postService);

        $posts = $this->postService->getPosts();

        $this->assertInstanceOf(ModelCollection::class, $posts);

        $this->assertCount(10, $posts);

        $post = $posts->first();

        $this->assertInstanceOf(Post::class, $post);
    }

    public function testPostServiceGetPaginatedPosts()
    {
        $this->assertIsObject($this->postService);

        $paginatedPosts = $this->postService->getPosts(self::PER_PAGE, self::CURRENT_PAGE);

        $this->assertInstanceOf(Paginator::class, $paginatedPosts);

        $posts = $paginatedPosts->data();

        $this->assertInstanceOf(ModelCollection::class, $posts);

        $this->assertCount(10, $posts);

        $post = $posts->first();

        $this->assertInstanceOf(Post::class, $post);
    }

    public function testPostServiceGetPostsWithSearch()
    {
        $randomKey = array_rand($this->initialPosts);

        $post = $this->initialPosts[$randomKey];

        $searchTerm = substr($post->title, 10, 10);

        $paginatedPosts = $this->postService->getPosts(self::PER_PAGE, self::CURRENT_PAGE, $searchTerm);

        $this->assertInstanceOf(Paginator::class, $paginatedPosts);

        $posts = $paginatedPosts->data();

        $this->assertInstanceOf(ModelCollection::class, $posts);

        $this->assertCount(1, $posts);
    }

    public function testPostServiceGetSinglePost()
    {
        $paginatedPosts = $this->postService->getPosts(self::PER_PAGE, self::CURRENT_PAGE);

        $post = $this->postService->getPost($paginatedPosts->data()->first()->uuid);

        $this->assertInstanceOf(Post::class, $post);

        $postData = $post->asArray();

        $this->assertArrayHasKey('title', $postData);

        $this->assertArrayHasKey('content', $postData);

        $this->assertArrayHasKey('updated_at', $postData);

    }

    public function testPostServiceGetMyPosts()
    {
        $users = $this->authService->getAll();

        $userUuid = $users->first()->uuid;

        $myPosts = $this->postService->getMyPosts($userUuid);

        $this->assertInstanceOf(ModelCollection::class, $myPosts);

        $this->assertCount(10, $myPosts);
    }

    public function testPostServiceAddNewPost()
    {
        $date = date('Y-m-d H:i:s');

        $users = $this->authService->getAll();

        $userUuid = $users->first()->uuid;

        $newPost = $this->postService->addPost([
            'user_uuid' => $userUuid,
            'title' => 'Just another post',
            'content' => 'Content of just another post',
            'image' => '',
            'updated_at' => $date
        ]);

        $uuid = $newPost['uuid'];

        $post = $this->postService->getPost($uuid);

        $this->assertEquals('Just another post', $post->title);

        $this->assertEquals('Content of just another post', $post->content);

        $this->assertEquals($date, $post->updated_at);

        $this->postService->deletePost($uuid);
    }

    public function testPostServiceUpdatePost()
    {
        $date = date('Y-m-d H:i:s');

        $posts = $this->postService->getPosts();

        $uuid = $posts->first()->uuid;

        $this->postService->updatePost($uuid, [
            'title' => 'Walt Disney Jr.',
            'content' => 'The best way to get started is to quit talking and begin doing.',
            'image' => 'image.jpg',
            'updated_at' => $date
        ]);

        $post = $this->postService->getPost($uuid);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $post->title);

        $this->assertEquals('Walt Disney Jr.', $post->title);

        $this->assertEquals('The best way to get started is to quit talking and begin doing.', $post->content);

        $this->assertEquals('image.jpg', $post->image);

        $this->assertEquals($date, $post->updated_at);
    }

    public function testPostServiceDeletePost()
    {
        $this->assertCount(10, $this->postService->getPosts());

        $users = $this->authService->getAll();

        $userUuid = $users->first()->uuid;

        $post = $this->postService->addPost([
            'user_uuid' => $userUuid,
            'title' => 'Just another post',
            'content' => 'Content of just another post',
            'image' => '',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->assertCount(11, $this->postService->getPosts());

        $this->postService->deletePost($post['uuid']);

        $this->assertCount(10, $this->postService->getPosts(self::PER_PAGE, self::CURRENT_PAGE)->data());
    }

    public function testPostServiceSaveAndDeleteImage()
    {
        $this->fileMeta = [
            'size' => 500,
            'name' => 'foo.jpg',
            'tmp_name' => base_dir() . DS . 'tmp' . DS . 'php8fe1.tmp',
            'type' => 'image/jpg',
            'error' => 0,
        ];

        $users = $this->authService->getAll();

        $userUuid = $users->first()->uuid;

        $uploadedFile = new UploadedFile($this->fileMeta);

        $image = $this->postService->saveImage($uploadedFile, $userUuid, 'poster');

        $this->assertFileExists(uploads_dir() . DS . $userUuid . DS . $image);

        $this->postService->deleteImage($userUuid . DS . $image);

        $this->assertFileDoesNotExist(uploads_dir() . DS . $userUuid . DS . $image);
    }
}