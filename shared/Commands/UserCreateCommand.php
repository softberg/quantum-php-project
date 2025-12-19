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
use Quantum\Di\Exceptions\DiException;
use Quantum\Libraries\Validation\Rule;
use Quantum\Libraries\Hasher\Hasher;
use Shared\Services\AuthService;
use Quantum\Console\QtCommand;
use ReflectionException;
use Shared\Models\User;

/**
 * Class UserCreateCommand
 * @package Shared\Commands
 */
class UserCreateCommand extends QtCommand
{

    use CommandValidationTrait;

    /**
     * Command name
     * @var string
     */
    protected $name = 'user:create';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Allows to create a user';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to create a user record:' . PHP_EOL . 'php qt user:create `Email` `Password` `[Role]` `[Firstname]` `[Lastname]`';

    protected $validator;

    /**
     * Command arguments
     * @var array[]
     */
    protected $args = [
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
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     * @throws BaseException
     */
    public function exec()
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

        service(AuthService::class)->add([
            'uuid' => $this->getArgument('uuid'),
            'firstname' => $this->getArgument('firstname'),
            'lastname' => $this->getArgument('lastname'),
            'role' => $this->getArgument('role'),
            'email' => $this->getArgument('email'),
            'password' => (new Hasher())->hash($this->getArgument('password')),
            'image' => $this->getArgument('image'),
        ]);

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