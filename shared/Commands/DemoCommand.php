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

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Bluemmb\Faker\PicsumPhotosProvider;
use Quantum\Migration\MigrationTable;
use Quantum\Factory\ServiceFactory;
use Quantum\Factory\TableFactory;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Loader\Setup;
use Faker\Factory;

/**
 * Class DemoCommand
 * @package Shared\Commands
 */
class DemoCommand extends QtCommand
{

    /**
     * Command name
     * @var string
     */
    protected $name = 'install:demo';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Generates demo users and posts';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'The command will create demo users and posts for your project';

    /**
     * The default action for all confirmations
     * @var array
     */
    protected $options = [
        ['yes', 'y', 'none', 'Acceptance of the confirmations']
    ];

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @var PostService
     */
    protected $postService;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * How many users to create
     */
    const USER_COUNT = 3;

    /**
     * How many posts to create
     */
    const POST_COUNT_PER_USER = 4;

    /**
     * Default password for generated users
     */
    const DEFAULT_PASSWORD = 'password';

    /**
     * Command name of run migration
     */
    const COMMAND_MIGRATE = 'migration:migrate';

    /**
     * Command name of create user
     */
    const COMMAND_USER_CREATE = 'user:create';

    /**
     * Command name of create post
     */
    const COMMAND_POST_CREATE = 'post:create';

    /**
     * Command constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
        $this->faker->addProvider(new PicsumPhotosProvider($this->faker));
        $this->authService = ServiceFactory::get(AuthService::class);
        $this->postService = ServiceFactory::get(PostService::class);
    }

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        if (!config()->has('database') || !config()->has('database.current')) {
            config()->import(new Setup('config', 'database'));
        }

        if (!$this->getOption('yes')) {
            if (!$this->confirm('The operation will remove all previously created data and will create new dataset. Continue?')) {
                $this->info('Operation was canceled!');
                return;
            }
        }

        $this->cleanUp();

        for ($i = 1; $i <= self::USER_COUNT; $i++) {
            $this->runExternalCommand(self::COMMAND_USER_CREATE, $this->newUserData('editor'));
        }

        $users = $this->authService->getAll();

        foreach ($users as $user) {
            for ($i = 1; $i <= self::POST_COUNT_PER_USER; $i++) {
                $this->runExternalCommand(self::COMMAND_POST_CREATE, $this->newPostData($user['id']));
            }
        }

        $this->info('Demo project created successfully');
    }

    /**
     * Runs an external command
     * @throws \Exception
     */
    protected function runExternalCommand($commandName, $arguments)
    {
        $command = $this->getApplication()->find($commandName);
        $command->run(new ArrayInput($arguments), new NullOutput);
    }

    /**
     * User data
     * @param string $role
     * @return array
     */
    private function newUserData(string $role = ''): array
    {
        return [
            'firstname' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'role' => $role,
            'email' => $this->faker->email(),
            'password' => self::DEFAULT_PASSWORD,
        ];
    }

    /**
     * Post data
     * @param int $userId
     * @return array
     */
    private function newPostData(int $userId): array
    {
        return [
            'title' => str_replace(['"', '\'', '-'], '', $this->faker->realText(50)),
            'description' => str_replace(['"', '\'', '-'], '', $this->faker->realText(1000)),
            'image' => $this->faker->imageUrl(640, 480, true, 0),
            'user_id' => $userId,
        ];
    }

    /**
     * Cleanups the database
     * @throws \Quantum\Exceptions\DatabaseException
     */
    private function cleanUp()
    {
        switch (config()->get('database')['current']) {
            case 'mysql':
                if ((new TableFactory)->checkTableExists(MigrationTable::TABLE)) {
                    $migrationTable = new MigrationTable();
                    $migrationTable->up($this->tableFactory);
                }

                $this->runExternalCommand(self::COMMAND_MIGRATE, ['direction' => 'down']);
                $this->runExternalCommand(self::COMMAND_MIGRATE, ['direction' => 'up']);
                break;
            case 'sleekdb':
                $this->postService->deleteTable();
                $this->authService->deleteTable();
                break;
        }
    }
}
