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
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Validation\Rule;
use ReflectionException;

/**
 * Class PostCreateCommand
 * @package Shared\Commands
 */
class PostCreateCommand extends QtCommand
{

    use CommandValidationTrait;

    /**
     * Command name
     * @var string|null
     */
    protected ?string $name = 'post:create';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Allows to create a post record';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = 'Use the following format to create a post record:' . PHP_EOL . 'php qt post:create `Title` `Description` `[Image]` `[Author]`';

    /**
     * Command arguments
     * @var array[]
     */
    protected array $args = [
        ['title', 'required', 'Post title'],
        ['description', 'required', 'Post description'],
        ['user_uuid', 'required', 'Author uuid'],
        ['uuid', 'optional', 'Post uuid'],
        ['image', 'optional', 'Post image'],
    ];

    /**
     * Executes the command
     * @throws BaseException|DiException|ServiceException|ReflectionException
     */
    public function exec()
    {
        $this->initValidator();

        $data = [
            'title' => $this->getArgument('title'),
            'content' => $this->getArgument('description'),
        ];

        if (!$this->validate($this->validationRules(), $data)) {
            $this->error($this->firstError() ?? 'Validation failed');
            return;
        }

        service(PostService::class)->addPost([
            'uuid' => $this->getArgument('uuid'),
            'user_uuid' => $this->getArgument('user_uuid'),
            'title' => $this->getArgument('title'),
            'content' => $this->getArgument('description'),
            'image' => $this->getArgument('image'),
        ]);

        $this->info('Post created successfully');
    }

    /**
     * Validation rules
     * @return array[]
     */
    protected function validationRules(): array
    {
        return [
            'title' => [
                Rule::required(),
                Rule::minLen(10),
                Rule::maxLen(50),
            ],
            'content' => [
                Rule::required(),
                Rule::minLen(10),
                Rule::maxLen(1000),
            ],
        ];
    }
}