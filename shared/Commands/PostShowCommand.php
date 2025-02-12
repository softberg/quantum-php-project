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
 * @since 2.9.5
 */

namespace Shared\Commands;

use Symfony\Component\Console\Helper\Table;
use Shared\Transformers\PostTransformer;
use Quantum\Exceptions\ServiceException;
use Quantum\Di\Exceptions\DiException;
use Quantum\Factory\ServiceFactory;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use ReflectionException;

/**
 * Class PostShowCommand
 * @package Shared\Commands
 */
class PostShowCommand extends QtCommand
{

    /**
     * Posts per page
     */
    const POSTS_PER_PAGE = 20;

    /**
     * Current page
     */
    const CURRENT_PAGE = 1;

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
     * @throws ReflectionException
     * @throws ServiceException
     * @throws DiException
     */
    public function exec()
    {
        $postService = ServiceFactory::get(PostService::class);

        $postTransformer = new PostTransformer();

        $uuid = $this->getArgument('uuid');

        $rows = [];

        if ($uuid) {
            $post = $postService->getPost($uuid);

            if ($post) {
                $rows[] = $this->composeTableRow(current(transform([$post], $postTransformer)));
            } else {
                $this->error('The post is not found');
                return;
            }
        } else {
            $paginatedPosts = $postService->getPosts(self::POSTS_PER_PAGE, self::CURRENT_PAGE);

            $posts = transform($paginatedPosts->data(), $postTransformer);

            foreach ($posts as $post) {
                $rows[] = $this->composeTableRow($post);
            }
        }

        $table = new Table($this->output);

        $table->setHeaderTitle('Posts')
            ->setHeaders(['UUID', 'Title', 'Description', 'Author', 'Date'])
            ->setRows($rows)
            ->render();
    }

    /**
     * Composes a table row
     * @param array $item
     * @return array
     */
    private function composeTableRow(array $item): array
    {
        return [
            $item['uuid'] ?? '',
            $item['title'] ?? '',
            strlen($item['content']) < 50 ? $item['content'] : mb_substr($item['content'], 0, 50) . '...' ?? '',
            $item['author'],
            $item['date'] ?? ''
        ];
    }
}