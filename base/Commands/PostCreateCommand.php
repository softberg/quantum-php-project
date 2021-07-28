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
 * @since 2.3.0
 */

namespace Base\Commands;

use Quantum\Factory\ServiceFactory;
use Base\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


/**
 * Class PostCreateCommand
 * @package Base\Commands
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
    protected $help = 'Add title and description to create a post';

    /**
     * Command options
     * @var array
     */
    protected $options = [
        ['title', 't', 'required', 'Post title'],
        ['description', 'd', 'required', 'Post description'],
    ];

    /**
     * @return void
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);

        $postService = $serviceFactory->get(PostService::class);

        $post = [
            'title' => $this->getOption('title'),
            'content' => $this->getOption('description'),
            'author' => 'anonymous@qt.com',
            'image' => null,
            'updated_at' => date('m/d/Y H:i')
        ];

        $postService->addPost($post);

        $this->info('Post create successfully');
    }

}
