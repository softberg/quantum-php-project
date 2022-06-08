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
 * Class PostCreateCommand
 * @package Shared\Commands
 */
class PostCreateCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'post:create';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Allows to create a post record';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to create a post record:' . PHP_EOL . 'php qt post:create `Title` `Description` `[Image]` `[Author]`';

    /**
     * Command arguments
     * @var \string[][]
     */
    protected $args = [
        ['title', 'required', 'Post title'],
        ['description', 'required', 'Post description'],
        ['user_id', 'required', 'Post user_id'],
        ['image', 'optional', 'Post image'],
    ];

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);
        $postService = $serviceFactory->get(PostService::class);

        $post = [
            'user_id' => $this->getArgument('user_id'),
            'title' => $this->getArgument('title'),
            'content' => $this->getArgument('description'),
            'image' => $this->getArgument('image'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $postService->addPost($post);

        $this->info('Post created successfully');
    }

}
