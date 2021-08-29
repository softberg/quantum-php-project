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
 * @since 2.5.0
 */

namespace Base\Commands;

use Quantum\Factory\ServiceFactory;
use Quantum\Libraries\Hasher\Hasher;
use Base\Services\AuthService;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


/**
 * Class UserCreateCommand
 * @package Base\Commands
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
     * @throws \Quantum\Exceptions\DiException
     * @throws \ReflectionException
     */
    public function exec()
    {
        $serviceFactory = Di::get(ServiceFactory::class);
        $authService = $serviceFactory->get(AuthService::class);

        $user = [
            'firstname' => $this->getArgument('firstname'),
            'lastname' => $this->getArgument('lastname'),
            'role' => $this->getArgument('role'),
            'email' => $this->getArgument('email'),
            'password' => (new Hasher())->hash($this->getArgument('password')),
            'activationToken' => '',
            'rememberToken' => '',
            'resetToken' => '',
            'accessToken' => '',
            'refreshToken' => '',
            'otp' => '',
            'otpExpiry' => '',
            'otpToken' => '',

        ];

        $authService->add($user);

        $this->info('User created successfully');
    }

}
