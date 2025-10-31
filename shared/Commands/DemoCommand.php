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
     * @var array
     */
    private $generatedUsers = [];

    /**
     * @var array
     */
    private $generatedPosts = [];

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

        $steps = $this->getSteps();
        $progress = $this->initProgressBar(count($steps));

        foreach ($steps as $step) {
            $this->updateProgress($progress, $step['message']);
            $step['action']();
        }

        $progress->finish();
        $this->info(PHP_EOL . "Demo project created successfully");
    }

    /**
     * Provides the sequence of defined tasks to be executed.
     * @return array[]
     */
    private function getSteps(): array
    {
        return [
            [
                'message' => 'Creating API module',
                'action' => function() {
                    $this->createModule('Api', 'DemoApi', false);
                }
            ],
            [
                'message' => 'Creating Web module',
                'action' => function() {
                    $this->createModule('Web', 'DemoWeb', true);
                }
            ],
            [
                'message' => 'Cleaning up uploads & rebuilding database',
                'action' => function() {
                    $this->resetDatabase();
                }
            ],
            [
                'message' => 'Generating users',
                'action' => function() {
                    $this->generatedUsers = $this->generateUsers();
                }
            ],
            [
                'message' => 'Generating posts',
                'action' => function() {
                    $this->generatedPosts = $this->generatePosts($this->generatedUsers);
                }
            ],
            [
                'message' => 'Generating comments',
                'action' => function() {
                    $this->generateComments($this->generatedUsers, $this->generatedPosts);
                }
            ],
        ];
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
        $progress->setBarCharacter('■');
        $progress->setEmptyBarCharacter('-');
        $progress->setProgressCharacter('►');
        $progress->start();
        return $progress;
    }

    /**
     * Creates module
     * @param string $moduleName
     * @param string $template
     * @param bool $withAssets
     * @return void
     * @throws ExceptionInterface
     */
    private function createModule(string $moduleName, string $template, bool $withAssets): void
    {
        $this->runExternalCommand(self::COMMANDS['module_generate'], [
            'module' => $moduleName,
            '--yes' => true,
            '--template' => $template,
            '--with-assets' => $withAssets
        ]);
    }

    /**
     * Generate demo users.
     * @return array
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws ExceptionInterface
     * @throws HttpClientException
     * @throws ReflectionException
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
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws ExceptionInterface
     * @throws HttpClientException
     * @throws ReflectionException
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
     * @return void
     * @throws ExceptionInterface
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
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ErrorException
     * @throws HttpClientException
     * @throws ReflectionException
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
     * Get random user for comment except post owner
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
     * Cleans the uploads and resets the database.
     * @return void
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ExceptionInterface
     * @throws ReflectionException
     */
    private function resetDatabase(): void
    {
        $this->removeUploads();
        $this->rebuildDatabase();
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