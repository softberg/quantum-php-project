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
 * @since 2.3.0
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
    protected $help = 'Add f, l, e, p, r to create a user';

    /**
     * Command options
     * @var array
     */
    protected $options = [
        ['firstname', 'f', 'required', 'User firstname'],
        ['lastname', 'l', 'required', 'User lastname'],
        ['email', 'e', 'required', 'User email'],
        ['password', 'p', 'required', 'User password'],
        ['role', 'r','required', 'User role'],
    ];

    /**
     * @return void
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $hasher = new Hasher;
        $serviceFactory = Di::get(ServiceFactory::class);

        $authService = $serviceFactory->get(AuthService::class);

        $user = [
            'firstname' => $this->getOption('firstname'),
            'lastname' => $this->getOption('lastname'),
            'role' => $this->getOption('role'),
            'email' => $this->getOption('email'),
            'password' =>  $hasher->hash($this->getOption('password')),
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

        $this->info('User create successfully');
    }

}
