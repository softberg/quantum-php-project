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

use Symfony\Component\Console\Helper\Table;
use Quantum\Factory\ServiceFactory;
use Shared\Services\AuthService;
use Quantum\Console\QtCommand;

/**
 * Class UserShowCommand
 * @package Shared\Commands
 */
class UserShowCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'user:show';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Displays all users or a single user';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Use the following format to display user(s):' . PHP_EOL . 'php qt user:show `[User id]`';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['id', 'optional', 'User id']
    ];

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $userService = ServiceFactory::get(AuthService::class);

        $uuid = $this->getArgument('id');

        $rows = [];

        if ($uuid) {
            $user = $userService->getUser($uuid);

            if (!empty($user)) {
                $rows[] = $this->composerTableRow($user);
            } else {
                $this->error('The user is not found');
                return;
            }
        } else {
            $users = $userService->getAll();
            foreach ($users as $user) {
                $rows[] = $this->composerTableRow($user);
            }
        }

        $table = new Table($this->output);

        $table->setHeaderTitle('Users')
            ->setHeaders([
                'ID',
                'UUID',
                'Firstname',
                'Lastname',
                'Email',
                'Role',
                'Password',
                'ActivationToken',
                'RememberToken',
                'Reset Token',
                'Access Token',
                'Refresh Token',
                'OTP',
                'OTP Expiry',
                'OTP Token'
            ])
            ->setRows($rows)
            ->render();
    }

    private function composerTableRow(array $item)
    {
        return [
            $item['id'] ?? '',
            $item['uuid'] ?? '',
            $item['firstname'] ?? '',
            $item['lastname'] ?? '',
            $item['role'] ?? '',
            $item['username'] ?? '',
            $item['password'] ?? '',
            $item['activationToken'] ?? '',
            $item['rememberToken'] ?? '',
            $item['resetToken'] ?? '',
            $item['accessToken'] ?? '',
            $item['refreshToken'] ?? '',
            $item['otp'] ?? '',
            $item['otpExpiry'] ?? '',
            $item['otpToken'] ?? '',
        ];
    }
}
