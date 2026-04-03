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
use Quantum\App\Exceptions\BaseException;
use Quantum\Di\Exceptions\DiException;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Validation\Rule;
use Shared\DTOs\PostDTO;
use ReflectionException;

/**
 * Class PostUpdateCommand
 * @package Shared\Commands
 */
class PostUpdateCommand extends QtCommand
{
    use CommandValidationTrait;

    /**
     * Command name
     * @var string|null
     */
    protected ?string $name = 'post:update';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Allows to update a post record';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = 'Use the following format to update the post:' . PHP_EOL . 'php qt post:update `[Post uuid]` -t `Title` -d `Description` [-i `Image`]';

    /**
     * Command arguments
     * @var array<int, array<int|string, mixed>>
     */
    protected array $args = [
        ['uuid', 'required', 'Post uuid'],
    ];

    /**
     * Command options
     * @var array<int, array<int|string, mixed>>
     */
    protected array $options = [
        ['title', 't', 'optional', 'Post title'],
        ['description', 'd', 'optional', 'Post description'],
        ['image', 'i', 'optional', 'Post image'],
    ];

    /**
     * Executes the command
     * @throws BaseException|DiException|ServiceException|ReflectionException
     */
    public function exec(): void
    {
        $this->initValidator();

        $postService = service(PostService::class);

        $postId = $this->getArgument('uuid');

        $post = $postService->getPost($postId);

        if ($post->isEmpty()) {
            $this->error('The post is not found');
            return;
        }

        $data = [
            'title' => $this->getOption('title') ?: $post->title,
            'content' => $this->getOption('description') ?: $post->content,
        ];

        if (!$this->validate($this->validationRules(), $data)) {
            $this->error($this->firstError() ?? 'Validation failed');
            return;
        }

        $postDto = new PostDTO(
            $this->getOption('title') ?: $post->title,
            $this->getOption('description') ?: $post->content,
            null,
            $this->getOption('image') ?: $post->image ?? ''
        );

        $postService->updatePost($postId, $postDto);

        $this->info('Post updated successfully');
    }

    /**
     * Validation rules
     * @return array[]
     */
    protected function validationRules(): array
    {
        return [
            'title' => [
                Rule::minLen(10),
                Rule::maxLen(50),
            ],
            'content' => [
                Rule::minLen(10),
                Rule::maxLen(1000),
            ],
        ];
    }
}
