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
use Quantum\Validation\Rule;
use Shared\DTOs\CommentDTO;
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
     * @var string|null
     */
    protected ?string $name = 'comment:create';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Allows to create a comment record';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = 'Use the following format to create a comment record:' . PHP_EOL . 'php qt comment:create `Post UUID` `User UUID` `Content`';

    /**
     * Command arguments
     * @var array[]
     */
    protected array $args = [
        ['post_uuid', 'required', 'The post uuid the comment belongs to'],
        ['user_uuid', 'required', 'The user uuid who writes the comment'],
        ['content', 'required', 'Comment text'],
    ];

    /**
     * Executes the command
     * @throws DiException|ServiceException|BaseException|ReflectionException
     */
    public function exec(): void
    {
        $this->initValidator();

        $data = [
            'content' => $this->getArgument('content'),
        ];

        if (!$this->validate($this->validationRules(), $data)) {
            $this->error($this->firstError() ?? 'Validation failed');
            return;
        }

        $commentDto = new CommentDTO(
            $this->getArgument('post_uuid'),
            $this->getArgument('user_uuid'),
            trim($this->getArgument('content'))
        );

        service(CommentService::class)->addComment($commentDto);

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