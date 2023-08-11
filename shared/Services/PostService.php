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

use Quantum\Libraries\Transformer\TransformerInterface;
use Quantum\Exceptions\FileSystemException;
use Quantum\Exceptions\FileUploadException;
use Quantum\Libraries\Storage\UploadedFile;
use Quantum\Exceptions\DatabaseException;
use Quantum\Libraries\Storage\FileSystem;
use Shared\Transformers\PostTransformer;
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
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * Initialize the service
     */
    public function __init(PostTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts(): array
    {
        $posts = ModelFactory::get(Post::class)
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
            ->orderBy('updated_at', 'desc')
            ->get();

        return transform($posts, $this->transformer);
    }

    /**
     * Get post
     * @param string $uuid
     * @param bool $transformed
     * @return array|null
     */
    public function getPost(string $uuid, bool $transformed = true): ?array
    {
        $post = ModelFactory::get(Post::class)
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
            ->get();

        if (empty($post)) {
            return null;
        }

        return $transformed ? current(transform($post, $this->transformer)) : current($post)->asArray();
    }

    /**
     * Get post
     * @param int $userId
     * @return ?array
     */
    public function getMyPosts(int $userId): ?array
    {
        $posts = ModelFactory::get(Post::class)
            ->joinThrough(ModelFactory::get(User::class))
            ->criteria('user_id', '=', $userId)
            ->select(
                'posts.uuid',
                'title',
                'image',
                'updated_at',
                ['users.uuid' => 'user_directory']
            )
            ->get();

        return transform($posts, $this->transformer);
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
