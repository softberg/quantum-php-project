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

namespace Shared\Services;

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Libraries\Upload\File;
use Quantum\Factory\ModelFactory;
use Faker\Factory;
use Quantum\Mvc\QtService;
use Shared\Models\Post;
use Quantum\Di\Di;
use Shared\Models\User;

/**
 * Class PostService
 * @package Shared\Services
 */
class PostService extends QtService
{

    /**
     * @var \Quantum\Mvc\QtModel
     */
    private $postModel;

    private $userModel;

    /**
     * Initialise the service
     * @param \Quantum\Factory\ModelFactory $modelFactory
     */
    public function __init(ModelFactory $modelFactory)
    {
        $this->postModel = $modelFactory->get(Post::class);
        $this->userModel = $modelFactory->get(User::class);
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts(): array
    {
        return $this->userModel->joinTo($this->postModel, false)->get();
    }

    /**
     * Get post
     * @param int $id
     * @return ?array
     */
    public function getPost(string $uuid): ?array
    {
        return $this->postModel->findOneBy('uuid', $uuid)->asArray();
    }

    /**
     * Get post
     * @param string $id
     * @return ?array
     */
    public function getMyPosts(int $user_id): ?array
    {
        return $this->postModel->criteria('user_id', '=', $user_id)->get();
    }

    /**
     * Add post
     * @param array $data
     */
    public function addPost(array $data): array
    {
        $data['uuid'] = Factory::create()->uuid();
        $post = $this->postModel->create();
        $post->fillObjectProps($data);
        $post->save();

        return $data;
    }

    /**
     * Update post
     * @param string $uuid
     * @param array $data
     */
    public function updatePost(string $uuid, array $data)
    {
        $post = $this->postModel->findOneBy('uuid', $uuid);
        $post->fillObjectProps($data);
        $post->save();
    }

    /**
     * Deletes the post
     * @param string $uuid
     * @return bool
     */
    public function deletePost(string $uuid): bool
    {
        $post = $this->postModel->findOneBy('uuid', $uuid);
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
