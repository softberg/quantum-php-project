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
use Symfony\Component\Console\Helper\Table;
use Quantum\App\Exceptions\BaseException;
use Quantum\Di\Exceptions\DiException;
use Quantum\Model\ModelCollection;
use Shared\Services\AuthService;
use Quantum\Console\QtCommand;
use ReflectionException;

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
    protected $help = 'Use the following format to display user(s):' . PHP_EOL . 'php qt user:show `[User uuid]`';

    /**
     * Command arguments
     * @var array
     */
    protected $args = [
        ['uuid', 'optional', 'User uuid']
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
        $userService = service(AuthService::class);

        $uuid = $this->getArgument('uuid');

        if ($uuid) {
            $user = $userService->getUserByUuid($uuid);

            if ($user->isEmpty()) {
                $this->error('The user is not found');
                return;
            }

            $usersCollection = new ModelCollection();
            $usersCollection->add($user);
        } else {
            $usersCollection = $userService->getAll();
        }

        $rows = [];

        foreach ($usersCollection as $user) {
            $rows[] = $this->composeTableRow($user->asArray());
        }

        $table = new Table($this->output);

        $table->setHeaderTitle('Users')
            ->setHeaders(['UUID', 'Firstname', 'Lastname', 'Email', 'Role'])
            ->setRows($rows)
            ->render();
    }

    /**
     * Composes a table row
     * @param array $item
     * @return array
     */
    private function composeTableRow(array $item): array
    {
        return [
            $item['uuid'] ?? '',
            $item['firstname'] ?? '',
            $item['lastname'] ?? '',
            $item['email'] ?? '',
            $item['role'] ?? '',
        ];
    }
}