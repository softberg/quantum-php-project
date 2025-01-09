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
 * @since 2.9.0
 */

namespace Shared\Services;

use Quantum\Libraries\Database\PaginatorInterface;
use Quantum\Exceptions\FileSystemException;
use Quantum\Exceptions\FileUploadException;
use Quantum\Libraries\Storage\UploadedFile;
use Quantum\Exceptions\DatabaseException;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Exceptions\ConfigException;
use Quantum\Exceptions\ModelException;
use Quantum\Exceptions\LangException;
use Quantum\Exceptions\DiException;
use Quantum\Factory\ModelFactory;
use Gumlet\ImageResizeException;
use Quantum\Mvc\QtService;
use ReflectionException;
use Shared\Models\User;
use Shared\Models\Post;
use Quantum\Di\Di;
use Faker\Factory;

/**
 * Class PostService
 * @package Shared\Services
 */
class PostService extends QtService
{

    /**
     * Get posts
     * @param int $perPage
     * @param int $currentPage
     * @return PaginatorInterface
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function getPosts(int $perPage, int $currentPage, ?string $search = null): PaginatorInterface
    {
        $query = ModelFactory::get(Post::class)
            ->joinThrough(ModelFactory::get(User::class))
            ->select(
                'posts.uuid',
                'title',
                'content',
                'image',
                'updated_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.uuid' => 'user_directory']
            )
            ->orderBy('updated_at', 'desc');

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';

            $criterias = [
                ['title', 'LIKE', $searchTerm],
                ['content', 'LIKE', $searchTerm]
            ];

            $query->criterias($criterias);
        }

        return $query->paginate($perPage, $currentPage);
    }

    /**
     * Get post
     * @param string $uuid
     * @return Post|null
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function getPost(string $uuid): ?Post
    {
        return ModelFactory::get(Post::class)
            ->joinThrough(ModelFactory::get(User::class))
            ->criteria('uuid', '=', $uuid)
            ->select(
                'posts.uuid',
                'user_id',
                'title',
                'content',
                'image',
                'updated_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.uuid' => 'user_directory']
            )
            ->first();
    }

    /**
     *  Get post
     * @param int $userId
     * @return array|null
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function getMyPosts(int $userId): ?array
    {
        return ModelFactory::get(Post::class)
            ->joinThrough(ModelFactory::get(User::class))
            ->criteria('user_id', '=', $userId)
            ->select(
                'posts.uuid',
                'title',
                'content',
                'image',
                'updated_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.uuid' => 'user_directory']
            )
            ->get();
    }

    /**
     * Add post
     * @param array $data
     * @return array
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function addPost(array $data): array
    {
        $data['uuid'] = Factory::create()->uuid();

        $post = ModelFactory::get(Post::class)->create();
        $post->fillObjectProps($data);
        $post->save();

        return $data;
    }

    /**
     * Update post
     * @param string $uuid
     * @param array $data
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function updatePost(string $uuid, array $data)
    {
        $post = ModelFactory::get(Post::class)->findOneBy('uuid', $uuid);
        $post->fillObjectProps($data);
        $post->save();
    }

    /**
     * Deletes the post
     * @param string $uuid
     * @return bool
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function deletePost(string $uuid): bool
    {
        return ModelFactory::get(Post::class)->findOneBy('uuid', $uuid)->delete();
    }

    /**
     * Delete posts table
     */
    public function deleteTable()
    {
        ModelFactory::get(Post::class)->deleteTable();
    }

    /**
     * Saves the post images
     * @param UploadedFile $uploadedFile
     * @param string $imageDirectory
     * @param string $imageName
     * @return string
     * @throws ImageResizeException
     * @throws FileSystemException
     * @throws FileUploadException
     * @throws LangException
     */
    public function saveImage(UploadedFile $uploadedFile, string $imageDirectory, string $imageName): string
    {
        $uploadedFile->setName($imageName . '-' . random_number());
        $uploadedFile->save(uploads_dir() . DS . $imageDirectory);

        return $uploadedFile->getNameWithExtension();
    }

    /**
     * Deletes the post image
     * @param string $imagePath
     * @throws DiException
     * @throws ReflectionException
     */
    public function deleteImage(string $imagePath)
    {
        $fs = Di::get(FileSystem::class);

        if ($fs->exists(uploads_dir() . DS . $imagePath)) {
            $fs->remove(uploads_dir() . DS . $imagePath);
        }
    }

}
