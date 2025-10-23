<?php /** @noinspection ALL */

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
    ];

    /**
     * Predefined command mappings.
     */
    private const COMMANDS = [
        'migrate' => 'migration:migrate',
        'user_create' => 'user:create',
        'post_create' => 'post:create',
        'user_delete' => 'user:delete',
        'post_delete' => 'post:delete',
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

        $progress = $this->initProgressBar(5);

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
        $this->updateProgress($progress, "Generating and adding demo users and posts into database...");

        for ($i = 0; $i < self::COUNTS['users']; $i++) {
            $user = $this->generateUserData();
            $this->runExternalCommand(self::COMMANDS['user_create'], $user);

            for ($j = 0; $j < self::COUNTS['posts_per_user']; $j++) {
                $this->runExternalCommand(self::COMMANDS['post_create'], $this->generatePostData($user));
            }
        }

        $this->updateProgress($progress, "Done");
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
    protected function runExternalCommand(string $commandName, ?array $arguments = [])
    {
        $command = $this->getApplication()->find($commandName);
        $command->run(new ArrayInput($arguments), new NullOutput);
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
        $title = textCleanUp($this->faker->realText(50));

        $imageName = save_remote_image(
            $this->faker->imageUrl(640, 480, true, 0),
            $user['uuid'],
            $title
        );

        return [
            'title' => $title,
            'description' => textCleanUp($this->faker->realText(1000)),
            'image' => $imageName,
            'user_uuid' => $user['uuid'],
        ];
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
}