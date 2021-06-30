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
 * @since 2.0.0
 */

namespace Base\Services;

use Quantum\Loader\Loader;
use Quantum\Loader\Setup;

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
     * Path to posts repository
     * @var string
     */
    protected $postRepository = 'base' . DS . 'repositories' . DS . 'posts.php';

    /**
     * Init
     * @param \Quantum\Loader\Loader $loader
     * @throws \Quantum\Exceptions\LoaderException
     */
    public function __init(Loader $loader)
    {
        self::$posts = $loader->setup(new Setup('base' . DS . 'repositories', 'posts', true))->load();
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts()
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
     */
    public function addPost(array $post)
    {
        self::$posts[] = array_merge(['id' => auto_increment(self::$posts, 'id')], $post);
        $this->persist(base_dir() . DS . $this->postRepository, self::$posts);
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Quantum\Exceptions\DiException
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
            if (in_array($id, $postData)) {
                $postData = $post;
            }
        }

        $this->persist(base_dir() . DS . $this->postRepository, self::$posts);
        return true;

    }

    /**
     * Delete post
     * @param int $id
     * @return bool
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function deletePost(int $id): bool
    {
        foreach (self::$posts as $key => $post) {
            if ($post['id'] == $id) {
                unset(self::$posts[$key]);
                $this->persist(base_dir() . DS . $this->postRepository, self::$posts);
                return true;
            }
        }

        return false;
    }

}
