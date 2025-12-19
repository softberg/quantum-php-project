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

use Quantum\Service\Exceptions\ServiceException;
use Quantum\App\Exceptions\BaseException;
use Quantum\Libraries\Validation\Rule;
use Quantum\Di\Exceptions\DiException;
use Shared\Services\CommentService;
use Quantum\Console\QtCommand;
use ReflectionException;

/**
 * Class CommentCreateCommand
 * @package Shared\Commands
 */
class CommentCreateCommand extends QtCommand
{

    use CommandValidationTrait;

    /**
     * Command name
     * @var string
     */
    protected $name = 'comment:create';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Allows to create a comment record';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to create a comment record:' . PHP_EOL . 'php qt comment:create `Post UUID` `User UUID` `Content`';

    /**
     * Command arguments
     * @var array[]
     */
    protected $args = [
        ['post_uuid', 'required', 'The post uuid the comment belongs to'],
        ['user_uuid', 'required', 'The user uuid who writes the comment'],
        ['content', 'required', 'Comment text'],
    ];

    /**
     * Executes the command
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     * @throws BaseException
     */
    public function exec()
    {
        $this->initValidator();

        $data = [
            'content' => $this->getArgument('content'),
        ];

        if (!$this->validate($this->validationRules(), $data)) {
            $this->error($this->firstError() ?? 'Validation failed');
            return;
        }

        service(CommentService::class)->addComment([
            'post_uuid' => $this->getArgument('post_uuid'),
            'user_uuid' => $this->getArgument('user_uuid'),
            'content' => $this->getArgument('content'),
        ]);

        $this->info('Comment created successfully');
    }

    /**
     * Validation rules
     * @return array[]
     */
    protected function validationRules(): array
    {
        return [
            'content' => [
                Rule::required(),
                Rule::minLen(2),
                Rule::maxLen(100),
            ],
        ];
    }
}