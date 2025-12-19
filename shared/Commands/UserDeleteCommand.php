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
use Shared\Services\AuthService;
use Quantum\Console\QtCommand;
use ReflectionException;

/**
 * Class PostDeleteCommand
 * @package Shared\Commands
 */
class UserDeleteCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'user:delete';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Deletes a user by UUID or clears the entire posts table with confirmation';

    /**
     * Command help text
     * @var string
     */
    protected $help = <<<HELP
Usage:
- Delete a specific user: php qt user:delete `{user_uuid}`
- Delete all users (with confirmation): php qt user:delete
- Delete all users (without confirmation): php qt user:delete --yes
HELP;
    /**
     * Command arguments
     * @var array[]
     */

    protected $args = [
        ['uuid', 'optional', 'User uuid'],
    ];

    /**
     * Command options
     * @var array[]
     */
    protected $options = [
        ['yes', 'y', 'none', 'Skip confirmation and delete all users'],
    ];

    /**
     * Executes the command
     * @throws ReflectionException
     * @throws ServiceException
     * @throws DiException
     */
    public function exec()
    {
        $uuid = $this->getArgument('uuid');

        if ($uuid) {
            $this->deleteSingleUser($uuid);
            return;
        }

        if (!$this->getOption('yes')) {
            if (!$this->confirm('This will delete all users. Are you sure? (yes/no)')) {
                $this->info('Operation was canceled!');
                return;
            }
        }

        $this->deleteAllUsers();
    }

    /**
     * Deletes a single user
     * @param string $uuid
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     * @throws BaseException
     */
    private function deleteSingleUser(string $uuid)
    {
        $authService = service(AuthService::class);

        $authService->delete($uuid);

        $this->info("User with UUID '{$uuid}' deleted successfully");
    }

    /**
     * Deletes all users
     * @throws BaseException
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
     */
    private function deleteAllUsers()
    {
        service(AuthService::class)->deleteAllUsers();

        $this->info('All users have been deleted successfully');
    }
}