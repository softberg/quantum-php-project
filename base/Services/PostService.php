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
     * @param Loader $loader
     * @throws \Exception
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
        $posts = [];

        foreach (self::$posts as $id => $post) {
            $post['id'] = $id;
            $posts[$id] = $post;
        }

        return $posts;
    }

    /**
     * Get post
     * @param int $id
     * @return mixed|null
     */
    public function getPost($id)
    {
        if (isset(self::$posts[$id])) {
            return self::$posts[$id];
        }

        return null;
    }

    /**
     * Add post
     * @param array $post
     * @throws \Exception
     */
    public function addPost($post)
    {
        if (count(self::$posts) > 0) {
            self::$posts[count(self::$posts) + 1] =  $post;
        } else {
            self::$posts[1] = $post;
        }

        $this->persist(base_dir() . DS . $this->postRepository, self::$posts);
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function updatePost($id, $data)
    {
        if (isset(self::$posts[$id])) {
            foreach ($data as $key => $value) {
                self::$posts[$id][$key] = $value;
            }

            $this->persist(base_dir() . DS . $this->postRepository, self::$posts);
            return true;
        }

        return false;
    }

    /**
     * Delete post
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deletePost($id)
    {
        if (isset(self::$posts[$id])) {
            unset(self::$posts[$id]);
            $this->persist(base_dir() . DS . $this->postRepository, self::$posts);
            return true;
        } else {
            return false;
        }
    }

}
