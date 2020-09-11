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

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Mvc\QtService;
use Quantum\Loader\Loader;

/**
 * Class PostService
 * @package Base\Services
 */
class PostService extends QtService
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
     * @throws \Exception
     */
    public function __init()
    {
        $loaderSetup = (object) [
                    'module' => current_module(),
                    'env' => 'base/repositories',
                    'fileName' => 'posts',
                    'exceptionMessage' => ExceptionMessages::CONFIG_FILE_NOT_FOUND
        ];

        self::$posts = (new Loader())->setup($loaderSetup)->load();
    }

    /**
     * Get posts
     * @return array
     */
    public function getPosts()
    {
        $posts = [];

        foreach(self::$posts as $id => $post) {
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
            array_push(self::$posts, $post);
        } else {
            self::$posts[1] = $post;
        }
        $this->persist();
    }

    /**
     * Update post
     * @param int $id
     * @param array $data
     * @throws \Exception
     */
    public function updatePost($id, $data)
    {
        foreach ($data as $key => $value) {
            self::$posts[$id][$key] = $value;
        }

        $this->persist();
    }

    /**
     * Delete post
     * @param int $id
     * @throws \Exception
     */
    public function deletePost($id)
    {
        unset(self::$posts[$id]);
        $this->persist();
    }

    /**
     * Persists the changes
     * @throws \Exception
     */
    private function persist()
    {
        $fileSystem = new FileSystem();
        $file = base_dir() . DS . $this->postRepository;
        if ($fileSystem->exists($file)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . var_export(self::$posts, true) . ';';
            $fileSystem->put($file, $content);
        } else {
            throw new \Exception(_message(ExceptionMessages::CONFIG_FILE_NOT_FOUND, $file));
        }
    }

}
