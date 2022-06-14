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

namespace Shared\Commands;

use Quantum\Factory\ServiceFactory;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


/**
 * Class PostUpdateCommand
 * @package Shared\Commands
 */
class PostUpdateCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'post:update';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Allows to update a post record';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to update the post:' . PHP_EOL . 'php qt post:update `[Post uuid]` -t `Title` -d `Description` [-i `Image`] [-a `Author`]';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['uuid', 'required', 'Post uuid']
    ];

    /**
     * Command options
     * @var array
     */
    protected $options = [
        ['title', 't', 'optional', 'Post title'],
        ['description', 'd', 'optional', 'Post description'],
        ['image', 'i', 'optional', 'Post image'],
        ['author', 'a', 'optional', 'Post author'],
    ];

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);

        $postService = $serviceFactory->get(PostService::class);

        $uuid = $this->getArgument('uuid');

        $post = $postService->getPost($uuid);

        if (!$post) {
            $this->error('The post is not found');
            return;
        }

        $title = $this->getOption('title');
        $description = $this->getOption('description');
        $image = $this->getOption('image');
        $author = $this->getOption('author');

        $postData = [
            'title' => $title ?: $post['title'],
            'content' => $description ?: $post['content'],
            'image' => $image ?: $post['image'] ?? '',
            'author' => $author ?: $post['author'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $postService->updatePost($uuid, $postData);

        $this->info('Post updated successfully');
    }

}
