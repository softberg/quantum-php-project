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

use Symfony\Component\Console\Helper\Table;
use Quantum\Factory\ServiceFactory;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


/**
 * Class PostShowCommand
 * @package Shared\Commands
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
    protected $description = 'Displays all posts or a single post';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to display post(s):' . PHP_EOL . 'php qt post:show `[Post uuid]`';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['uuid', 'optional', 'Post uuid']
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

        $rows = [];

        if ($uuid) {
            $post = $postService->getPost($uuid);

            if (!empty($post)) {
                $rows[] = [
                    $post['uuid'],
                    $post['title'],
                    strlen($post['content']) < 100 ? $post['content'] : mb_substr($post['content'], 0, 100) . '...',
                    $post['author'],
                    $post['updated_at']
                ];
            } else {
                $this->error('The post is not found');
                return;
            }
        } else {
            $posts = $postService->getPosts();

            foreach ($posts as $post) {
                $rows[] = [
                    $post['uuid'],
                    $post['title'],
                    strlen($post['content']) < 100 ? $post['content'] : mb_substr($post['content'], 0, 100) . '...',
                    $post['author'],
                    $post['updated_at']
                ];
            }
        }

        $table = new Table($this->output);

        $table->setHeaderTitle('Posts')
            ->setHeaders(['uuid', 'Title', 'Description', 'Author', 'Date'])
            ->setRows($rows)
            ->render();

    }

}
