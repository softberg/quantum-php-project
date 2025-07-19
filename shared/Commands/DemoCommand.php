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
 * @since 2.9.8
 */

namespace Shared\Commands;

use Quantum\Libraries\HttpClient\Exceptions\HttpClientException;
use Quantum\Libraries\Database\Exceptions\DatabaseException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Quantum\Libraries\Storage\Factories\FileSystemFactory;
use Quantum\Libraries\Database\Factories\TableFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Quantum\Service\Exceptions\ServiceException;
use Symfony\Component\Console\Input\ArrayInput;
use Quantum\Config\Exceptions\ConfigException;
use Quantum\Service\Factories\ServiceFactory;
use Quantum\Model\Exceptions\ModelException;
use Quantum\App\Exceptions\BaseException;
use Quantum\Libraries\Database\Database;
use Bluemmb\Faker\PicsumPhotosProvider;
use Quantum\Di\Exceptions\DiException;
use Quantum\Migration\MigrationTable;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use Quantum\Console\QtCommand;
use Quantum\Loader\Setup;
use ReflectionException;
use Faker\Generator;
use ErrorException;
use Faker\Factory;
use Exception;

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
     * @var Generator
     */
    protected $faker;

    /**
     * How many users to create
     */
    const USER_COUNT = 3;

    /**
     * How many posts to create
     */
    const POST_COUNT_PER_USER = 8;

    /**
     * Default password for generated users
     */
    const DEFAULT_PASSWORD = 'password';

    /**
     * Command name to run migrations
     */
    const COMMAND_MIGRATE = 'migration:migrate';

    /**
     * Command name to create users
     */
    const COMMAND_USER_CREATE = 'user:create';

    /**
     * Command name to create posts
     */
    const COMMAND_POST_CREATE = 'post:create';

    /**
     * Command name to generate modules
     */
    const COMMAND_CREATE_MODULE = 'module:generate';

    /**
     * @throws DiException
     * @throws ReflectionException
     * @throws ServiceException
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
     * @throws BaseException
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ErrorException
     * @throws ExceptionInterface
     * @throws HttpClientException
     * @throws ReflectionException
     */
    public function exec()
    {
        if (!config()->has('database') || !config()->has('database.default')) {
            config()->import(new Setup('config', 'database'));
        }

        if (!$this->getOption('yes')) {
            if (!$this->confirm('The operation will remove all previously created data and will create new dataset. Continue?')) {
                $this->info('Operation was canceled!');
                return;
            }
        }

        $progressBar = new ProgressBar($this->output, 5);

        $progressBar->setFormat(sprintf('%s <info>%%item%%</info>', $progressBar->getFormatDefinition('verbose')));

        $progressBar->start();

        $progressBar->setMessage("Cleaning up the database", 'item');

        $progressBar->display();

        $this->cleanUp();

        $progressBar->advance();

        $progressBar->setMessage("Adding new users into database...", 'item');

        $progressBar->display();

        for ($i = 1; $i <= self::USER_COUNT; $i++) {
            $this->runExternalCommand(self::COMMAND_USER_CREATE, $this->newUserData('editor'));
        }

        $progressBar->advance();

        $progressBar->setMessage("Adding posts for each user into database...", 'item');

        $progressBar->display();

        $users = $this->authService->getAll();

        foreach ($users as $user) {
            for ($i = 1; $i <= self::POST_COUNT_PER_USER; $i++) {
                $this->runExternalCommand(self::COMMAND_POST_CREATE, $this->newPostData($user));
            }
        }

        $progressBar->advance();

        $progressBar->setMessage("Creating demo api module...", 'item');

        $progressBar->display();

        $this->runExternalCommand(self::COMMAND_CREATE_MODULE, [
            "module" => "Api",
            "--yes" => true,
            "--template" => "DemoApi",
            "--with-assets" => false
        ]);

        $progressBar->advance();

        $progressBar->setMessage("Creating demo web module...", 'item');

        $progressBar->display();

        $this->runExternalCommand(self::COMMAND_CREATE_MODULE, [
            "module" => "Web",
            "--yes" => true,
            "--template" => "DemoWeb",
            "--with-assets" => true
        ]);

        $progressBar->advance();

        $progressBar->setMessage("Done", 'item');

        $progressBar->display();

        $progressBar->finish();

        $this->info("\nDemo project created successfully");
    }

    /**
     * Runs an external command
     * @throws Exception
     * @throws ExceptionInterface
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
     * @param $user
     * @return array
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws HttpClientException
     * @throws ReflectionException
     */
    private function newPostData($user): array
    {
        $title = $this->textCleanUp($this->faker->realText(50));

        $imageName = save_remote_image(
            $this->faker->imageUrl(640, 480, true, 0),
            $user->uuid,
            $title
        );

        return [
            'title' => $title,
            'description' => $this->textCleanUp($this->faker->realText(1000)),
            'image' => $imageName,
            'user_id' => $user->id,
        ];
    }

    /**
     * Cleanups the database
     * @throws BaseException
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ExceptionInterface
     * @throws ReflectionException
     * @throws ModelException
     */
    private function cleanUp()
    {
        $this->removeFolders();

        $databaseDriver = Database::getInstance()->getConfigs()['driver'];

        switch ($databaseDriver) {
            case 'mysql':
                $tableFactory = new TableFactory();

                if (!$tableFactory->checkTableExists(MigrationTable::TABLE)) {
                    $migrationTable = new MigrationTable();
                    $migrationTable->up($tableFactory);
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

    /**
     * @param string $text
     * @return array|string|string[]
     */
    private function textCleanUp(string $text)
    {
        return str_replace(['"', '\'', '-'], '', $text);
    }

    /**
     * Removes users folders
     * @throws DiException
     * @throws ReflectionException
     * @throws BaseException
     * @throws ConfigException
     */
    private function removeFolders()
    {
        $fs = FileSystemFactory::get();

        $uploadsFolder = $fs->glob(uploads_dir() . DS . '*');

        foreach ($uploadsFolder as $folder) {
            $userImages = $fs->glob($folder . DS . '*');

            foreach ($userImages as $file) {
                $fs->remove($file);
            }

            $fs->removeDirectory($folder);
        }
    }
}