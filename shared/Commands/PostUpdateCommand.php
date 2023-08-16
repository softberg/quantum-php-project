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

namespace Shared\Commands;

use Quantum\Exceptions\DiException;
use Quantum\Factory\ServiceFactory;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;

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
    ];

    /**
     * Executes the command
     * @throws DiException
     */
    public function exec()
    {
        $postService = ServiceFactory::get(PostService::class);

        $postId = $this->getArgument('uuid');

        $post = $postService->getPost($postId, false);

        if (!$post) {
            $this->error('The post is not found');
            return;
        }

        $postData = [
            'title' => $this->getOption('title') ?: $post['title'],
            'content' => $this->getOption('description') ?: $post['content'],
            'image' => $this->getOption('image') ?: $post['image'] ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $postService->updatePost($postId, $postData);

        $this->info('Post updated successfully');
    }

}
