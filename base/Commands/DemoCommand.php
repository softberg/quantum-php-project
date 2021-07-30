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
 * @since 2.3.0
 */

namespace Base\Commands;

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;
use Faker\Factory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;


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
    protected $name = 'demo:post';
    protected $faker;
    /**
     * Command description
     * @var string
     */
    protected $description = 'Generates posts.php and users.php files';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'The command will generate new posts.php and users.php files';


    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
    }

    /**
     * @return void
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $userCommand =  'user:create'; 
        $postCommand = 'post:create'; 
       
        $this->createFile('users');

        $adminArguments = $this->createUser('admin');
        $guestArguments = $this->createUser();
        
        $this->runCommand($adminArguments, $userCommand);
        $this->runCommand($guestArguments, $userCommand);
      

        $this->createFile('posts');

        
        for ($i = 1; $i <= 6; $i++) {
            $postArguments = [
                '-t' => $this->faker->realText(30),
                '-d' => $this->faker->realText(),
                '-i' => $this->faker->imageUrl(480, 480, 'animals', true, 'cats'),
                '-a' => $adminArguments['-u'],
            ];
            $this->runCommand($postArguments, $postCommand);
        }
         
    }

    protected function runCommand($arguments, $commandName){
        $command = $this->getApplication()->find($commandName);
        $greetInput = new ArrayInput($arguments);
        $output = new NullOutput;
        $command->run($greetInput, $output);
    }

    protected function createFile($file)
    {
        $fs = Di::get(FileSystem::class);
       
        $repositoryDir = BASE_DIR . DS . 'base' . DS . 'repositories';
        $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' .  export([]) . ';';

        $fs->put($repositoryDir . DS . $file . '.php', $content);

        $this->info(ucfirst($file) . ' successfully generated');
    }


    private function createUser($role = '')
    {
        $data =
            [
                '-f' => $this->faker->name(),
                '-l' => $this->faker->lastName(),
                '-r' => $role,
                '-u' => $this->faker->email(),
                '-p' => '123456',
            ];

        return $data;
    }


}
