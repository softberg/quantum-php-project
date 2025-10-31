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

use Quantum\Libraries\HttpClient\Exceptions\HttpClientException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Quantum\Libraries\Storage\Factories\FileSystemFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Quantum\Service\Exceptions\ServiceException;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Quantum\Config\Exceptions\ConfigException;
use Quantum\App\Exceptions\BaseException;
use Quantum\Libraries\Database\Database;
use Bluemmb\Faker\PicsumPhotosProvider;
use Quantum\Di\Exceptions\DiException;
use Quantum\Console\QtCommand;
use Ottaviano\Faker\Gravatar;
use ReflectionException;
use Shared\Enums\Role;
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
     * @var Generator
     */
    protected $faker;

    /**
     * Default password for generated users
     */
    const DEFAULT_PASSWORD = 'password';

    /**
     * Static values for user and post counts.
     */
    private const COUNTS = [
        'users' => 3,
        'posts_per_user' => 8,
        'comments_per_post' => 3,
    ];

    /**
     * Predefined command mappings.
     */
    private const COMMANDS = [
        'migrate' => 'migration:migrate',
        'user_create' => 'user:create',
        'post_create' => 'post:create',
        'comment_create' => 'comment:create',
        'user_delete' => 'user:delete',
        'post_delete' => 'post:delete',
        'comment_delete' => 'comment:delete',
        'module_generate' => 'module:generate',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->faker = Factory::create();
        $this->faker->addProvider(new PicsumPhotosProvider($this->faker));
        $this->faker->addProvider(new Gravatar($this->faker));
    }

    /**
     * Executes the command
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws ExceptionInterface
     * @throws HttpClientException
     * @throws ReflectionException
     * @throws ServiceException
     */
    public function exec()
    {
        if (!$this->getOption('yes')) {
            if (!$this->confirm('The operation will remove all previously created data and will create new dataset. Continue?')) {
                $this->info('Operation was canceled!');
                return;
            }
        }

        $progress = $this->initProgressBar(6);

        $this->createModules($progress);
        $this->resetDatabase($progress);
        $this->generateDemoData($progress);

        $progress->finish();

        $this->info(PHP_EOL . "Demo project created successfully");
    }

    /**
     * Initializes a progress bar.
     * @param int $steps
     * @return ProgressBar
     */
    private function initProgressBar(int $steps): ProgressBar
    {
        $progress = new ProgressBar($this->output, $steps);
        $progress->setFormat(sprintf('%s <info>%%item%%</info>', $progress->getFormatDefinition('verbose')));
        $progress->start();
        return $progress;
    }

    /**
     * Creates demo modules.
     * @param ProgressBar $progress
     * @throws ExceptionInterface
     */
    private function createModules(ProgressBar $progress): void
    {
        $this->updateProgress($progress, "Creating demo api module...");

        $this->runExternalCommand(self::COMMANDS['module_generate'], [
            'module' => 'Api',
            '--yes' => true,
            '--template' => 'DemoApi',
            '--with-assets' => false
        ]);

        $this->updateProgress($progress, "Creating demo web module...");

        $this->runExternalCommand(self::COMMANDS['module_generate'], [
            'module' => 'Web',
            '--yes' => true,
            '--template' => 'DemoWeb',
            '--with-assets' => true
        ]);
    }

    /**
     * Generates demo users and posts.
     * @param ProgressBar $progress
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws ExceptionInterface
     * @throws HttpClientException
     * @throws ReflectionException
     */
    private function generateDemoData(ProgressBar $progress): void
    {
        $this->updateProgress($progress, "Generating demo users...");
        $createdUsers = $this->generateUsers();

        $this->updateProgress($progress, "Generating demo posts...");
        $createdPosts = $this->generatePosts($createdUsers);

        $this->updateProgress($progress, "Generating demo comments...");
        $this->generateComments($createdUsers, $createdPosts);
    }

    /**
     * Generate demo users.
     * @param ProgressBar $progress
     * @return array
     * @throws ExceptionInterface
     */
    private function generateUsers(): array
    {
        $users = [];
        for ($i = 0; $i < self::COUNTS['users']; $i++) {
            $user = $this->generateUserData();
            $this->runExternalCommand(self::COMMANDS['user_create'], $user);
            $users[] = $user;
        }
        return $users;
    }

    /**
     * Generate demo posts for each user.
     * @param array $users
     * @return array
     */
    private function generatePosts(array $users): array
    {
        $posts = [];
        foreach ($users as $user) {
            for ($i = 0; $i < self::COUNTS['posts_per_user']; $i++) {
                $post = $this->generatePostData($user);
                $this->runExternalCommand(self::COMMANDS['post_create'], $post);
                $posts[] = $post;
            }
        }
        return $posts;
    }

    /**
     * Generate demo comments for each post.
     * @param array $users
     * @param array $posts
     */
    private function generateComments(array $users, array $posts): void
    {
        foreach ($posts as $post) {
            for ($i = 0; $i < self::COUNTS['comments_per_post']; $i++) {
                $commentUser = $this->getRandomUserExcept($users, $post['user_uuid']);

                $this->runExternalCommand(self::COMMANDS['comment_create'], [
                    'post_uuid' => $post['uuid'],
                    'user_uuid' => $commentUser['uuid'],
                    'content'   => textCleanUp($this->faker->realText(rand(20, 100))),
                ]);
            }
        }
    }

    /**
     * Generates data for user
     * @return array
     */
    private function generateUserData(): array
    {
        $userUuid = $this->faker->uuid();
        $email = textCleanUp($this->faker->email());

        create_user_directory($userUuid);

        $imageName = save_remote_image(
            $this->faker->gravatarUrl(),
            $userUuid,
            $email
        );

        return [
            'uuid' => $userUuid,
            'firstname' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'role' => Role::EDITOR,
            'email' => $email,
            'password' => self::DEFAULT_PASSWORD,
            'image' => $imageName
        ];
    }

    /**
     * Generates data for post
     * @param $user
     * @return array
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws HttpClientException
     * @throws ReflectionException
     */
    private function generatePostData($user): array
    {
        $postUuid = $this->faker->uuid();
        $title = textCleanUp($this->faker->realText(50));

        $imageName = save_remote_image(
            $this->faker->imageUrl(640, 480, true, 0),
            $user['uuid'],
            $title
        );

        return [
            'uuid' => $postUuid,
            'title' => $title,
            'description' => textCleanUp($this->faker->realText(1000)),
            'image' => $imageName,
            'user_uuid' => $user['uuid'],
        ];
    }

    /**
     * @param array $users
     * @param string $excludeUuid
     * @return array
     */
    private function getRandomUserExcept(array $users, string $excludeUuid): array
    {
        $candidates = array_filter($users, function($u) use ($excludeUuid) {
            return $u['uuid'] !== $excludeUuid;
        });

        $candidates = array_values($candidates);
        return $candidates[array_rand($candidates)];
    }

    /**
     * Cleans the uploads and resets the database.
     * @param ProgressBar $progress
     * @return void
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ExceptionInterface
     * @throws ReflectionException
     * @throws ServiceException
     */
    private function resetDatabase(ProgressBar $progress): void
    {
        $this->updateProgress($progress, "Cleaning up the database");
        $this->removeUploads();
        $this->rebuildDatabase();
    }

    /**
     * Rebuilds the database
     * @throws ExceptionInterface
     */
    private function rebuildDatabase(): void
    {
        switch (Database::getInstance()->getConfigs()['driver']) {
            case 'mysql':
                $this->runExternalCommand(self::COMMANDS['migrate'], ['direction' => 'down']);
                $this->runExternalCommand(self::COMMANDS['migrate'], ['direction' => 'up']);
                break;

            case 'sleekdb':
                $this->runExternalCommand(self::COMMANDS['comment_delete'], ['--yes' => true]);
                $this->runExternalCommand(self::COMMANDS['post_delete'], ['--yes' => true]);
                $this->runExternalCommand(self::COMMANDS['user_delete'], ['--yes' => true]);
                break;
        }
    }

    /**
     * Removes uploaded folders and files.
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ReflectionException
     */
    private function removeUploads(): void
    {
        $fs = FileSystemFactory::get();

        foreach ($fs->glob(uploads_dir() . DS . '*') as $folder) {
            foreach ($fs->glob($folder . DS . '*') as $file) {
                $fs->remove($file);
            }

            $fs->removeDirectory($folder);
        }
    }

    /**
     * Generates demo users and posts.
     * @param ProgressBar $progress
     * @param string $message
     */
    private function updateProgress(ProgressBar $progress, string $message): void
    {
        $progress->setMessage($message, 'item');
        $progress->display();
        $progress->advance();
    }

    /**
     * Runs an external command
     * @throws Exception
     * @throws ExceptionInterface
     */
    private function runExternalCommand(string $commandName, ?array $arguments = [])
    {
        $command = $this->getApplication()->find($commandName);
        $command->run(new ArrayInput($arguments), new NullOutput);
    }
}