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

use Symfony\Component\Console\Helper\Table;
use Quantum\Factory\ServiceFactory;
use Base\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


/**
 * Class PostShowCommand
 * @package Base\Commands
 */
class PostShowCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'post:show';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Displays posts';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Displays all posts or single post on terminal';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['id', 'optional', 'Post ID']
    ];

    /**
     * @return void
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);

        $postService = $serviceFactory->get(PostService::class);

        $posts = $postService->getPosts();

        $id = $this->getArgument('id');

        $rows = [];

        if ($id) {
            foreach ($posts as $post) {
                if ($post['id'] == $id) {
                    $rows[] = [$post['id'], $post['title'], $post['content']];
                }
            }
        } else {
            foreach ($posts as $post) {
                $rows[] = [$post['id'], $post['title'], $post['content']];
            }
        }

        $table = new Table($this->output);

        $table->setHeaderTitle('Posts')
            ->setHeaders(['ID', 'Title', 'Description'])
            ->setRows($rows)
            ->render();

    }

}
