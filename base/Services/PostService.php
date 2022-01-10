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
use Quantum\Mvc\QtService;
use Base\Models\Post;
use Quantum\Di\Di;

/**
 * Class PostService
 * @package Base\Services
 */
class PostService extends QtService
{

    /**
     * @var \Quantum\Mvc\QtModel
     */
    private $postModel;

    /**
     * Initialise the service
     * @param \Quantum\Factory\ModelFactory $modelFactory
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
     * @return ?array
     */
    public function getPost(int $id): ?array
    {
        return $this->postModel->findOne($id)->asArray();
    }

    /**
     * Add post
     * @param array $data
     */
    public function addPost(array $data)
    {
        $post = $this->postModel->create();
        $post->fillObjectProps($data);
        $post->save();
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     */
    public function updatePost(int $id, array $data)
    {
        $post = $this->postModel->findOne($id);
        $post->fillObjectProps($data);
        $post->save();
    }

    /**
     * Deletes the post
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id): bool
    {
        $post = $this->postModel->findOne($id);
        return $post->delete();
    }

    /**
     * Delete posts table
     */
    public function deleteTable()
    {
        $this->postModel->deleteTable();
    }

    /**
     * Saves the post images
     * @param \Quantum\Libraries\Upload\File $file
     * @param string $imageName
     * @return string
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
     */
    public function deleteImage(string $imageUrl)
    {
        $fs = Di::get(FileSystem::class);
        $pathParts = explode('/', $imageUrl);
        $imageName = end($pathParts);

        if ($fs->exists(uploads_dir() . DS . $imageName)) {
            $fs->remove(uploads_dir() . DS . $imageName);
        }
    }

}
