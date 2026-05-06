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
use Quantum\Validation\Validator;
use Shared\Services\AuthService;
use Quantum\Console\CliCommand;
use Quantum\Validation\Rule;
use Quantum\Hasher\Hasher;
use Shared\DTOs\UserDTO;
use ReflectionException;
use Shared\Models\User;

/**
 * Class UserCreateCommand
 * @package Shared\Commands
 */
class UserCreateCommand extends CliCommand
{
    use CommandValidationTrait;

    /**
     * Command name
     * @var string|null
     */
    protected ?string $name = 'user:create';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Allows to create a user';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = 'Use the following format to create a user record:' . PHP_EOL . 'php qt user:create `Email` `Password` `[Role]` `[Firstname]` `[Lastname]`';

    protected Validator $validator;

    /**
     * Command arguments
     * @var array<int, array<int|string, mixed>>
     */
    protected array $args = [
        ['email', 'required', 'User email'],
        ['password', 'required', 'User password'],
        ['firstname', 'required', 'User firstname'],
        ['lastname', 'required', 'User lastname'],
        ['uuid', 'optional', 'User uuid'],
        ['role', 'optional', 'User role'],
        ['image', 'optional', 'User image'],
    ];

    /**
     * Executes the command
     * @throws DiException|ServiceException|BaseException|ReflectionException
     */
    public function exec(): void
    {
        $this->initValidator();

        $data = [
            'email' => $this->getArgument('email'),
            'password' => $this->getArgument('password'),
            'firstname' => $this->getArgument('firstname'),
            'lastname' => $this->getArgument('lastname'),
        ];

        if (!$this->validate($this->validationRules(), $data)) {
            $this->error($this->firstError() ?? 'Validation failed');
            return;
        }

        $userDto = new UserDTO(
            $this->getArgument('email'),
            (new Hasher())->hash($this->getArgument('password')),
            $this->getArgument('firstname'),
            $this->getArgument('lastname'),
            $this->getArgument('role') ?? '',
            $this->getArgument('uuid'),
            $this->getArgument('image') ?? ''
        );

        service(AuthService::class)->add($userDto->toArray());

        $this->info('User created successfully');
    }

    /**
     * Validation rules
     * @return array[]
     */
    protected function validationRules(): array
    {
        return [
            'email' => [
                Rule::required(),
                Rule::email(),
                Rule::unique(User::class, 'email'),
            ],
            'password' => [
                Rule::required(),
                Rule::minLen(6),
            ],
            'firstname' => [
                Rule::required(),
            ],
            'lastname' => [
                Rule::required(),
            ],
        ];
    }
}
