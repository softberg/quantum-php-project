<?php

declare(strict_types=1);

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 3.0.0
 */

namespace Shared\Commands;

use Quantum\Service\Exceptions\ServiceException;
use Symfony\Component\Console\Helper\Table;
use Quantum\App\Exceptions\BaseException;
use Quantum\Di\Exceptions\DiException;
use Quantum\Model\ModelCollection;
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
    public const POSTS_PER_PAGE = 20;

    /**
     * Current page
     */
    public const CURRENT_PAGE = 1;

    /**
     * Command name
     * @var string|null
     */
    protected ?string $name = 'post:show';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Displays all posts or a single post';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = 'Use the following format to display post(s):' . PHP_EOL . 'php qt post:show `[Post uuid]`';

    /**
     * Command arguments
     * @var array<int, array<int|string, mixed>>
     */
    protected array $args = [
        ['uuid', 'optional', 'Post uuid'],
    ];

    /**
     * Executes the command
     * @throws BaseException|DiException|ServiceException|ReflectionException
     */
    public function exec(): void
    {
        $postService = service(PostService::class);

        $uuid = $this->getArgument('uuid');

        if ($uuid) {
            $post = $postService->getPost($uuid);

            if ($post->isEmpty()) {
                $this->error('The post is not found');
                return;
            }

            $postCollection = new ModelCollection();
            $postCollection->add($post);
        } else {
            $postCollection = $postService
                ->getPosts(self::POSTS_PER_PAGE, self::CURRENT_PAGE)
                ->data();
        }

        $transformedPosts = $postService->transformData($postCollection->all());

        $rows = [];

        foreach ($transformedPosts as $post) {
            $rows[] = $this->composeTableRow($post, 50);
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
     * @param int $maxContentLength
     * @return array
     */
    private function composeTableRow(array $item, int $maxContentLength = 25): array
    {
        $content = strip_tags($item['content'] ?? '');

        $content = str_replace(["\r", "\n"], ' ', $content);
        if (mb_strlen($content) > $maxContentLength) {
            $content = mb_substr($content, 0, $maxContentLength) . '...';
        }

        return [
            $item['uuid'] ?? '',
            $item['title'] ?? '',
            $content,
            $item['author'],
            $item['date'] ?? '',
        ];
    }
}
