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

use Quantum\Service\Factories\ServiceFactory;
use Shared\Services\CommentService;
use Quantum\Console\QtCommand;

/**
 * Class CommentCreateCommand
 * @package Shared\Commands
 */
class CommentCreateCommand extends QtCommand
{
    protected $name = 'comment:create';

    protected $description = 'Allows to create a comment record';

    protected $help = 'Use the following format to create a comment record:' . PHP_EOL . 'php qt comment:create `Post UUID` `User UUID` `Content`';

    protected $args = [
        ['post_uuid', 'required', 'The post uuid the comment belongs to'],
        ['user_uuid', 'required', 'The user uuid who writes the comment'],
        ['content', 'required', 'Comment text'],
    ];

    public function exec()
    {
        $commentService = ServiceFactory::create(CommentService::class);

        $comment = [
            'post_uuid' => $this->getArgument('post_uuid'),
            'user_uuid' => $this->getArgument('user_uuid'),
            'content' => $this->getArgument('content'),
        ];

        $commentService->addComment($comment);

        $this->info('Comment created successfully');
    }
}