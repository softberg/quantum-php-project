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
 * @since 2.9.9
 */

namespace Shared\Commands;

use Quantum\Service\Exceptions\ServiceException;
use Quantum\App\Exceptions\BaseException;
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
    protected $description = 'Deletes a post by UUID or clears the entire posts table with confirmation';

    /**
     * Command help text
     * @var string
     */
    protected $help = <<<HELP
Usage:
- Delete a specific post: php qt post:delete `{post_uuid}`
- Delete all posts (with confirmation): php qt post:delete
- Delete all posts (without confirmation): php qt post:delete --yes
HELP;

    /**
     * Command arguments
     * @var array[]
     */
    protected $args = [
        ['uuid', 'optional', 'Post uuid'],
    ];

    /**
     * Command options
     * @var array[]
     */
    protected $options = [
        ['yes', 'y', 'none', 'Skip confirmation and delete all posts'],
    ];

    /**
     * Executes the command
     * @throws ReflectionException
     * @throws ServiceException
     * @throws DiException
     */
    public function exec()
    {
        $uuid = $this->getArgument('uuid');

        if ($uuid) {
            $this->deleteSinglePost($uuid);
            return;
        }

        if (!$this->getOption('yes')) {
            if (!$this->confirm('This will delete all posts. Are you sure? (yes/no)')) {
                $this->info('Operation was canceled!');
                return;
            }
        }

        $this->deleteAllPosts();
    }

    /**
     * Deletes a single post
     * @param string $uuid
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     * @throws BaseException
     */
    private function deleteSinglePost(string $uuid)
    {
        $postService = service(PostService::class);

        $post = $postService->getPost($uuid);

        if ($post->isEmpty()) {
            $this->error('The post is not found');
            return;
        }

        $postService->deletePost($uuid);

        $this->info("Post with UUID '{$uuid}' deleted successfully");
    }

    /**
     * Deletes all posts
     * @throws BaseException
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     */
    private function deleteAllPosts()
    {
       service(PostService::class)->deleteAllPosts();

        $this->info('All posts have been deleted successfully');
    }
}