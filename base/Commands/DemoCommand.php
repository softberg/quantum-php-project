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
use Quantum\Libraries\Hasher\Hasher;
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
        $usersCollection = [];
        $postsCollection = [];

        $adminData = $this->createUser(1, 'admin');
        $guestData = $this->createUser(2);
        array_push($usersCollection, $adminData, $guestData);

        $this->persists($usersCollection, 'users');


        $author =  $adminData['email'];

        for ($i = 1; $i <= 6; $i++) {
            $data = $this->createPost($author, $i);
            array_push($postsCollection, $data);
        }

        $this->persists($postsCollection, 'posts');
    }


    protected function persists($collection, $file)
    {
        $fs = Di::get(FileSystem::class);
        $repositoryDir = BASE_DIR . DS . 'base' . DS . 'repositories';
        $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' .  export($collection) . ';';

        $fs->put($repositoryDir . DS . $file . '.php', $content);

        $this->info(ucfirst($file) . ' successfully generated');
    }


    private function createUser($id, $role = '')
    {
        $hasher = new Hasher;

        $data =
            [
                'id' => $id,
                'firstname' => $this->faker->name(),
                'lastname' => $this->faker->lastName(),
                'role' => $role,
                'email' => $this->faker->email(),
                'password' => $hasher->hash('password'),
                'activation_token' => '',
                'remember_token' => '',
                'reset_token' => '',
                'access_token' => '',
                'refresh_token' => '',
                'otp' => '',
                'otp_expires' => '',
                'otp_token' => '',
            ];

        return $data;
    }


    private function createPost($author, $id)
    {

        $data =
            [
                'id'      => $id,
                'title'   => $this->faker->realText(30),
                'content' => $this->faker->realText(),
                'author'  => $author,
                'image'   => $this->faker->imageUrl(360, 360, 'animals', true, 'cats'),
                'updated_at' => date("d/m/Y  H:i"),
            ];

        return $data;
    }
}
