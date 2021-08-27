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
 * @since 2.5.0
 */

namespace Base\Commands;

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Quantum\Libraries\Storage\FileSystem;
use Bluemmb\Faker\PicsumPhotosProvider;
use Quantum\Console\QtCommand;
use WW\Faker\Provider\Picture;
use Quantum\Di\Di;
use Faker\Factory;


/**
 * Class PostCreateCommand
 * @package Base\Commands
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
    protected $help = 'The command will create 2 new files (users.php and posts.php) and will generate records';

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * How many posts to create
     */
    const POST_COUNT = 6;

    /**
     * Default password for generated users
     */
    const DEFAULT_PASSWORD = 'password';

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
    }

    /**
     * @throws \ReflectionException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function exec()
    {
        $this->createFile('users');

        $adminArguments = $this->newUser('admin');
        $guestArguments = $this->newUser();

        $this->runCommand(self::COMMAND_USER_CREATE, $adminArguments);
        $this->runCommand(self::COMMAND_USER_CREATE, $guestArguments);

        $this->createFile('posts');

        for ($i = 1; $i <= self::POST_COUNT; $i++) {
            $postArguments = [
                'title' => $this->faker->realText(30),
                'description' => $this->faker->realText(500),
                'image' => $this->faker->imageUrl(640, 480, true, 0),
                'author' => $adminArguments['email'],
            ];

            $this->runCommand(self::COMMAND_POST_CREATE, $postArguments);
        }

    }

    /**
     * Runs the external command
     * @throws \Exception
     */
    protected function runCommand($commandName, $arguments)
    {
        $command = $this->getApplication()->find($commandName);
        $command->run(new ArrayInput($arguments), new NullOutput);
    }

    /**
     * Creates new repo file
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     * @throws \Quantum\Exceptions\DiException
     */
    protected function createFile($file)
    {
        $fs = Di::get(FileSystem::class);

        $repositoryDir = BASE_DIR . DS . 'base' . DS . 'repositories';
        $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . export([]) . ';';

        $fs->put($repositoryDir . DS . $file . '.php', $content);

        $this->info(ucfirst($file) . ' successfully generated');
    }

    /**
     * Creates new user
     * @param string $role
     * @return array
     */
    private function newUser(string $role = ''): array
    {
        return [
            'firstname' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'role' => $role,
            'email' => $this->faker->email(),
            'password' => self::DEFAULT_PASSWORD,
        ];
    }


}
