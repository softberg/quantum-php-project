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
    protected $description = 'Displays all posts or a single post';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to display post(s):' . PHP_EOL . 'php qt post:show `[Post Id]`';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['id', 'optional', 'Post ID']
    ];

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);

        $postService = $serviceFactory->get(PostService::class);

        $id = $this->getArgument('id');

        $rows = [];

        if ($id) {
            $post = $postService->getPost($id);

            if (!empty($post)) {
                $rows[] = [
                    $post['id'],
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
                    $post['id'],
                    $post['title'],
                    strlen($post['content']) < 100 ? $post['content'] : mb_substr($post['content'], 0, 100) . '...',
                    $post['author'],
                    $post['updated_at']
                ];
            }
        }

        $table = new Table($this->output);

        $table->setHeaderTitle('Posts')
            ->setHeaders(['ID', 'Title', 'Description', 'Author', 'Date'])
            ->setRows($rows)
            ->render();

    }

}
