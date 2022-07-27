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
 * @since 2.8.0
 */

namespace Shared\Services;

use Shared\Transformers\PostSleekTransformer;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Libraries\Upload\File;
use Quantum\Factory\ModelFactory;
use Quantum\Mvc\QtService;
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
     * @var \Quantum\Libraries\Transformer\TransformerInterface
     */
    private $transformer;

    /**
     * Initialize the service
     */
    public function __init(PostSleekTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts(): array
    {
        $posts = ModelFactory::get(Post::class)->joinThrough(ModelFactory::get(User::class))->orderBy('updated_at', 'desc')->get();

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
        $post = ModelFactory::get(Post::class)->joinThrough(ModelFactory::get(User::class))->criteria('uuid', '=', $uuid)->get();

        if (empty($post)) {
            return null;
        }

        return $transformed ? current(transform($post, $this->transformer)) : current($post);
    }

    /**
     * Get post
     * @param string $userId
     * @return ?array
     */
    public function getMyPosts(int $userId): ?array
    {
        return ModelFactory::get(Post::class)->criteria('user_id', '=', $userId)->get();
    }

    /**
     * Add post
     * @param array $data
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
