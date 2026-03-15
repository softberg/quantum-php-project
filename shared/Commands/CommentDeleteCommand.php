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
 * @since 3.0.0
 */

namespace Shared\Commands;

use Quantum\Service\Exceptions\ServiceException;
use Quantum\App\Exceptions\BaseException;
use Quantum\Di\Exceptions\DiException;
use Shared\Services\CommentService;
use Quantum\Console\QtCommand;
use ReflectionException;

/**
 * Class CommentDeleteCommand
 * @package Shared\Commands
 */
class CommentDeleteCommand extends QtCommand
{
    /**
     * Command name
     * @var string|null
     */
    protected ?string $name = 'comment:delete';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Deletes a comment by UUID or clears the entire comments table with confirmation';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = <<<HELP
Usage:
- Delete a specific comment: php qt comment:delete `{comment_uuid}`
- Delete all comments (with confirmation): php qt comment:delete
- Delete all comments (without confirmation): php qt comment:delete --yes
HELP;

    /**
     * Command arguments
     * @var array[]
     */
    protected array $args = [
        ['uuid', 'optional', 'Comment uuid'],
    ];

    /**
     * Command options
     * @var array[]
     */
    protected array $options = [
        ['yes', 'y', 'none', 'Skip confirmation and delete all comments'],
    ];

    /**
     * Executes the command
     * @throws BaseException|DiException|ServiceException|ReflectionException
     */
    public function exec()
    {
        $uuid = $this->getArgument('uuid');

        if ($uuid) {
            $this->deleteSingleComment($uuid);
            return;
        }

        if (!$this->getOption('yes')) {
            if (!$this->confirm('This will delete all comments. Are you sure? (yes/no)')) {
                $this->info('Operation was canceled!');
                return;
            }
        }

        $this->deleteAllComments();
    }

    /**
     * Deletes a single comment
     * @param string $uuid
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     * @throws BaseException
     */
    private function deleteSingleComment(string $uuid)
    {
        $commentService = service(CommentService::class);

        $comment = $commentService->getComment($uuid);

        if ($comment->isEmpty()) {
            $this->error('The comment is not found');
            return;
        }

        $commentService->deleteComment($uuid);

        $this->info("Comment with UUID '{$uuid}' deleted successfully");
    }

    /**
     * Deletes all comments
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     * @throws BaseException
     */
    private function deleteAllComments()
    {
        service(CommentService::class)->deleteAllComments();

        $this->info('All comments have been deleted successfully');
    }
}