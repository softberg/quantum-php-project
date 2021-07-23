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
use Quantum\Libraries\Upload\File;
use Quantum\Console\QtCommand;
use Quantum\Di\Di;


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

    /**
     * @return void
     * @throws \Quantum\Exceptions\DiException
     */
    public function exec()
    {
        $postsCollection = ([
            [
                'id' => 1,
                'title' => 'Walt Disney',
                'content' => 'The way to get started is to quit talking and begin doing.',
                'author' => 'admin@qt.com',
                'image' => 'walt-disney-9252507491.jpg',
                'updated_at' => '07/22/2021 16:31',
            ],
            [
                'id' => 2,
                'title' => 'James Cameron',
                'content' => 'If you set your goals ridiculously high and it is a failure, you will fail above everyone else success.',
                'author' => 'admin@qt.com',
                'image' => null,
                'updated_at' => '05/08/2021 23:13',
            ],
        ]);

        $this->renderContent($postsCollection, 'posts');

        $usersCollection = ([
            [
                'id' => '1',
                'firstname' => 'Admin',
                'lastname' => 'Hunter',
                'role' => 'admin',
                'email' => 'admin@qt.com',
                'password' => '$2y$12$4Y4/1a4308KEiGX/xo6vgO41szJuDHC7KhpG5nknx/xxnLZmvMyGi',
                'activation_token' => '',
                'remember_token' => '',
                'reset_token' => '',
                'access_token' => '',
                'refresh_token' => '',
                'otp' => '',
                'otp_expires' => '',
                'otp_token' => '',
            ],
        ]);

        $this->renderContent($usersCollection, 'users');
      
    }


    protected function renderContent($collection, $file)
    {
        $fs = Di::get(FileSystem::class);
        $repositoryDir = BASE_DIR . DS . 'base' . DS . 'repositories';
        $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' .  export($collection) . ';';

        $fs->put($repositoryDir . DS . $file .'.php', $content);

        $this->info(ucfirst($file). ' successfully generated');
    }
}
