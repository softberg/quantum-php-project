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
use Shared\Services\AuthService;
use Quantum\Console\CliCommand;
use ReflectionException;

/**
 * Class PostDeleteCommand
 * @package Shared\Commands
 */
class UserDeleteCommand extends CliCommand
{
    /**
     * Command name
     * @var string|null
     */
    protected ?string $name = 'user:delete';

    /**
     * Command description
     * @var string|null
     */
    protected ?string $description = 'Deletes a user by UUID or clears the entire users table with confirmation';

    /**
     * Command help text
     * @var string|null
     */
    protected ?string $help = <<<HELP
Usage:
- Delete a specific user: php qt user:delete `{user_uuid}`
- Delete all users (with confirmation): php qt user:delete
- Delete all users (without confirmation): php qt user:delete --yes
HELP;
    /**
     * Command arguments
     * @var array<int, array<int|string, mixed>>
     */
    protected array $args = [
        ['uuid', 'optional', 'User uuid'],
    ];

    /**
     * Command options
     * @var array<int, array<int|string, mixed>>
     */
    protected array $options = [
        ['yes', 'y', 'none', 'Skip confirmation and delete all users'],
    ];

    /**
     * Executes the command
     * @throws DiException|ServiceException|BaseException|ReflectionException
     */
    public function exec(): void
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
     * @throws BaseException|DiException|ServiceException|ReflectionException
     */
    private function deleteSingleUser(string $uuid): void
    {
        $authService = service(AuthService::class);

        $authService->delete($uuid);

        $this->info("User with UUID '{$uuid}' deleted successfully");
    }

    /**
     * Deletes all users
     * @throws BaseException|DiException|ServiceException|ReflectionException
     */
    private function deleteAllUsers(): void
    {
        service(AuthService::class)->deleteAllUsers();

        $this->info('All users have been deleted successfully');
    }
}
