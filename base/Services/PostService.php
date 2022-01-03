<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.6.0
 */

namespace Base\Services;

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Libraries\Upload\File;
use Quantum\Factory\ModelFactory;
use Quantum\Loader\Loader;
use Quantum\Loader\Setup;
use Base\Models\Post;

/**
 * Class PostService
 * @package Base\Services
 */
class PostService extends BaseService
{
    private $postModel;
    /**
     * Posts
     * @var array
     */
    protected static $posts = [];

    /**
     * Initialise the service
     * @param \Quantum\Loader\Loader $loader
     * @param \Quantum\Loader\Setup $setup
     * @param array $args
     * @throws \Quantum\Exceptions\LoaderException
     */
    public function __init(ModelFactory $modelFactory)
    {
        $this->postModel = $modelFactory->get(Post::class);
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts(): array
    {
        return $this->postModel->get();
    }

    /**
     * Get post
     * @param int $id
     * @return mixed|null
     */
    public function getPost(int $id)
    {
        return $this->postModel->findOne($id)->asArray();
    }

    /**
     * Add post
     * @param array $post
     * @throws \Quantum\Exceptions\DiException
     */
    public function addPost(array $post)
    {
        $data = $this->postModel->create();

        foreach ($post as $key => $value) {
            $data->$key = $value ?? "";
        }

        $data->save();
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     * @throws \Quantum\Exceptions\DiException
     */
    public function updatePost(int $id, array $data)
    {
        $post = $this->postModel->findOne($id);
        foreach ($data as $key => $value) {
            $post->$key = $value;
        }

        $post->save();
    }

    /**
     * Delete post
     * @param int $id
     * @return void
     * @throws \Quantum\Exceptions\DiException
     */
    public function deletePost(int $id):void
    {
        $post = $this->postModel->findOne($id);
        $post->delete();    
    }

    /**
     * Saves the post images
     * @param \Quantum\Libraries\Upload\File $file
     * @param string $imageName
     * @return string
     * @throws \Quantum\Exceptions\FileUploadException
     */
    public function saveImage(File $file, string $imageName): string
    {
        $file->setName($imageName . '-' . random_number());
        $file->save(uploads_dir());

        return $file->getNameWithExtension();
    }

    /**
     * Deletes the post image
     * @param string $imageUrl
     * @throws \Quantum\Exceptions\DiException
     */
    public function deleteImage(string $imageUrl)
    {
        $postImage = $this->postModel->findOneBy('image', $imageUrl);

        if($postImage){
            $postImage->image = "";
        }

        $postImage->save();
    }

}
