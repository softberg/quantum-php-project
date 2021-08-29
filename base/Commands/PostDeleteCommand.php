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

namespace Base\Commands;

use Quantum\Factory\ServiceFactory;
use Base\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


/**
 * Class PostDeleteCommand
 * @package Base\Commands
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
    protected $help = 'Use the following format to delete a post:' . PHP_EOL . 'php qt post:delete `Post Id`';

    /**
     * Command arguments
     * @var \string[][]
     */
    protected $args = [
        ['id', 'required', 'Post ID'],
    ];

    /**
     * @throws \Quantum\Exceptions\DiException
     * @throws \ReflectionException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);

        $postService = $serviceFactory->get(PostService::class);

        $id = $this->getArgument('id');

        if($postService->deletePost($id)) {
            $this->info('Post deleted successfully');
        } else {
            $this->error('No such post found');
        }
    }

}
