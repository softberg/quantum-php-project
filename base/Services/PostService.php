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
 * @since 1.9.0
 */

namespace Base\Services;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Mvc\Qt_Service;
use Quantum\Loader\Loader;

/**
 * 
 */
class PostService extends Qt_Service
{

    protected static $posts = [];
    
    protected $postRepository = 'base' . DS . 'repositories' . DS . 'posts.php';

    public function __init()
    {
        $loaderSetup = (object) [
                    'module' => current_module(),
                    'env' => 'base/repositories',
                    'fileName' => 'posts',
                    'exceptionMessage' => ExceptionMessages::CONFIG_FILE_NOT_FOUND
        ];

        $loader = new Loader($loaderSetup);

        self::$posts = is_array($loader->load()) ? $loader->load() : [];
    }

    public function getPosts()
    {
        $posts = [];

        foreach(self::$posts as $id => $post) {
            $post['id'] = $id;
            $posts[$id] = $post;
        }

        return $posts;
    }

    public function getPost($id)
    {
        if (isset(self::$posts[$id])) {
            return self::$posts[$id];
        }

        return null;
    }

    public function addPost($post)
    {
        if (count(self::$posts) > 0) {
            array_push(self::$posts, $post);
        } else {
            self::$posts[1] = $post;
        }
        $this->persist();
    }

    public function updatePost($id, $data)
    {
        foreach ($data as $key => $value) {
            self::$posts[$id][$key] = $value;
        }

        $this->persist();
    }

    public function deletePost($id)
    {
        unset(self::$posts[$id]);
        $this->persist();
    }

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
