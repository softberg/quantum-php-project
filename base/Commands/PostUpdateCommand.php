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
 * Class PostUpdateCommand
 * @package Base\Commands
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
    protected $help = 'Find post by ID and update the title or description of post';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['id', 'required', 'Post ID']
    ];

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

        $id = $this->getArgument('id');

        $title = $this->getOption('title');
        $description = $this->getOption('description');

        if($title && $description) {
            $post = [
                'title' => $title,
                'content' => $description,
                'author' => 'anonymous@qt.com',
                'updated_at' => date('m/d/Y H:i')
            ];

            $postService->updatePost($id, $post);

            $this->info('Post updated successfully');
        } else {
            $this->error('Missing post title or description');
        }
    }

}
