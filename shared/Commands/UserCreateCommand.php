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
 * @since 2.8.0
 */

namespace Shared\Commands;

use Quantum\Factory\ServiceFactory;
use Quantum\Libraries\Hasher\Hasher;
use Shared\Services\AuthService;
use Quantum\Console\QtCommand;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Validation\Rule;
use Quantum\Libraries\Validation\Validator;
use Shared\Models\User;

/**
 * Class UserCreateCommand
 * @package Shared\Commands
 */
class UserCreateCommand extends QtCommand
{

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

    /**
     * Command arguments
     * @var \string[][]
     */
    protected $args = [
        ['email', 'required', 'User email'],
        ['password', 'required', 'User password'],
        ['role', 'optional', 'User role'],
        ['firstname', 'optional', 'User firstname'],
        ['lastname', 'optional', 'User lastname'],
    ];

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        if (!$this->emailValidate($this->getArgument('email'))) {
            $this->error("The email field must be a valid email address");
            return;
        }
        $authService = ServiceFactory::get(AuthService::class);
        

        $user = [
            'firstname' => $this->getArgument('firstname'),
            'lastname' => $this->getArgument('lastname'),
            'role' => $this->getArgument('role'),
            'email' => $this->getArgument('email'),
            'password' => (new Hasher())->hash($this->getArgument('password')),
        ];

        $authService->add($user);

        $this->info('User created successfully');
    }
    /**
     * Validate email
     * @param string $email
     * @return boolean
     */
    private function emailValidate(string $email)
    {
        $validator = new Validator();
        $validator->addValidation('uniqueUser', function ($value) {
            $userModel = ModelFactory::get(User::class);
            return empty($userModel->findOneBy('email', $value)->asArray());
        });
        $validator->addRules([
            'email' => [
                Rule::set('required'),
                Rule::set('email'),
                Rule::set('uniqueUser')
            ],
        ]);

        return $validator->isValid(['email' => $email]);
    }
}
