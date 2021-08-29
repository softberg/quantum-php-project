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
 * @since 2.5.0
 */

namespace Base\Services;

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Libraries\Upload\File;
use Quantum\Loader\Loader;
use Quantum\Loader\Setup;
use Quantum\Di\Di;

/**
 * Class PostService
 * @package Base\Services
 */
class PostService extends BaseService
{

    /**
     * Posts
     * @var array
     */
    protected static $posts = [];

    /**
     * Init
     * @param \Quantum\Loader\Loader $loader
     * @throws \Quantum\Exceptions\LoaderException
     */
    public function __init(Loader $loader)
    {
        $loader = $loader->setup(new Setup('base' . DS . 'repositories', 'posts', true));

        $this->repository = $loader->getFilePath();

        self::$posts = $loader->load();
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts(): array
    {
        return self::$posts;
    }

    /**
     * Get post
     * @param int $id
     * @return mixed|null
     */
    public function getPost(int $id)
    {
        foreach (self::$posts as $post) {
            if ($post['id'] == $id) {
                return $post;
            }
        }

        return null;
    }

    /**
     * Add post
     * @param array $post
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     * @throws \ReflectionException
     */
    public function addPost(array $post)
    {
        self::$posts[] = array_merge(['id' => auto_increment(self::$posts, 'id')], $post);
        $this->persist(self::$posts);
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Quantum\Exceptions\DiException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function updatePost(int $id, array $data): bool
    {
        $post = $this->getPost($id);

        if (empty($post)) {
            return false;
        }

        foreach ($data as $key => $value) {
            $post[$key] = $value;
        }

        foreach (self::$posts as &$postData) {
            if ($id == $postData['id']) {
                $postData = $post;
            }
        }

        $this->persist(self::$posts);
        return true;
    }

    /**
     * Delete post
     * @param int $id
     * @return bool
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     * @throws \ReflectionException
     */
    public function deletePost(int $id): bool
    {
        foreach (self::$posts as $key => $post) {
            if ($post['id'] == $id) {
                unset(self::$posts[$key]);
                $this->persist(self::$posts);
                return true;
            }
        }

        return false;
    }

    /**
     * Saves the post images
     * @param \Quantum\Libraries\Upload\File $file
     * @param string $imageName
     * @return string
     * @throws \Gumlet\ImageResizeException
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
     * @throws \ReflectionException
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
