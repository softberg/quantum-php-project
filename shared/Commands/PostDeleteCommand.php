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
 * @since 2.9.7
 */

namespace Shared\Commands;

use Quantum\Service\Exceptions\ServiceException;
use Quantum\Service\Factories\ServiceFactory;
use Quantum\Di\Exceptions\DiException;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use ReflectionException;

/**
 * Class PostDeleteCommand
 * @package Shared\Commands
 */
class PostDeleteCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'post:delete';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Allows to delete a post record';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to delete a post:' . PHP_EOL . 'php qt post:delete `Post uuid`';

    /**
     * Command arguments
     * @var array[]
     */
    protected $args = [
        ['uuid', 'required', 'Post uuid'],
    ];

    /**
     * Executes the command
     * @throws ReflectionException
     * @throws ServiceException
     * @throws DiException
     */
    public function exec()
    {
        $postService = ServiceFactory::get(PostService::class);

        $uuid = $this->getArgument('uuid');

        $post = $postService->getPost($uuid, false);

        if (!$post) {
            $this->error('The post is not found');
            return;
        }

        $postService->deletePost($uuid);

        $this->info('Post deleted successfully');
    }
}