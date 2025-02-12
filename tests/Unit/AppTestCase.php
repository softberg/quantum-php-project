<?php

namespace Quantum\Tests\Unit;

use Quantum\Libraries\Storage\Factories\FileSystemFactory;
use Quantum\App\Factories\AppFactory;
use Quantum\Environment\Environment;
use Quantum\Libraries\Config\Config;
use Quantum\Factory\ServiceFactory;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use PHPUnit\Framework\TestCase;
use Quantum\Loader\Setup;
use Quantum\App\App;

class AppTestCase extends TestCase
{

    protected $fs;

    protected $authService;

    protected $postService;

    public function setUp(): void
    {
        $this->fs = FileSystemFactory::get();

        AppFactory::create(App::WEB, dirname(__DIR__) . DS . '_root');

        Environment::getInstance()
            ->setMutable(true)
            ->load(new Setup('config', 'env'));

        Config::getInstance()->load(new Setup('config', 'config', true));

        $this->authService = ServiceFactory::get(AuthService::class);

        $this->postService = ServiceFactory::get(PostService::class);
    }

    public function tearDown(): void
    {
        $this->removeFolders();
    }

    protected function removeFolders()
    {
        $uploadsFolder = $this->fs->glob(uploads_dir() . DS . '*');

        foreach ($uploadsFolder as $folder) {
            $this->fs->removeDirectory($folder);
        }
    }
}