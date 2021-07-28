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
        $faker = Factory::create();
        $roles = ['admin', ''];
        $usersCollectionData = [];

        for ($i = 0; $i < 2; $i++) {
            $data = $this->createUser($faker, $roles, $i);
            array_push($usersCollectionData, $data);
        }
        
        $this->renderContent($usersCollectionData, 'users');

        $author =  $usersCollectionData[0]['email'];

        $postCollectionData = $this->createPosts($faker, $author);
        $this->renderContent($postCollectionData, 'posts');
      
    }


    protected function renderContent($collection, $file)
    {
        $fs = Di::get(FileSystem::class);
        $repositoryDir = BASE_DIR . DS . 'base' . DS . 'repositories';
        $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' .  export($collection) . ';';

        $fs->put($repositoryDir . DS . $file .'.php', $content);

        $this->info(ucfirst($file). ' successfully generated');
    }


    private function createUser($faker, $roles, $i){
        $hasher = new Hasher;
        
            $data = 
            [
                'id' => $i+1,
                'firstname' => $faker->name(),
                'lastname' => $faker->lastName(),
                'role' => $roles[$i],
                'email' => $faker->email(),
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


    private function createPosts($faker, $author){
        $postsCollection = [];

        for ($i = 1; $i <= 6; $i++) {
            $data = 
                [
                    'id'      => $i,
                    'title'   => $faker->realText(30),
                    'content' => $faker->realText(),
                    'author'  => $author,  
                    'image'   => $faker->imageUrl(360, 360, 'animals', true, 'cats'),
                    'updated_at' => date("d/m/Y  H:i"),
                ];
            
            array_push( $postsCollection, $data);
        }

        return $postsCollection;
    }

}
