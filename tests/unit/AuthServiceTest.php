<?php

use PHPUnit\Framework\TestCase;
use Base\Services\AuthService;
use Quantum\Factory\ServiceFactory;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Loader\Loader;
use Quantum\Di\Di;

class AuthServiceTest extends TestCase
{

    public $authService;
    private $initialUser = [
        'email' => 'admin@qt.com',
        'password' => '$2y$12$0M78WcmUZYQq85vHZLoNW.CyDUezRxh9Ye8/Z8oWCwJmBrz8p.j7C',
        'firstname' => 'Admin',
        'lastname' => 'User',
        'role' => 'admin',
        'remember_token' => '',
        'reset_token' => '',
    ];

    public function setUp(): void
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $loader = new Loader(new FileSystem);

        $loader->loadFile(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'constants.php');

        $loader->loadDir(HELPERS_DIR . DS . 'functions');

        Di::loadDefinitions();

        $reflectionProperty = new \ReflectionProperty(Di::class, 'dependencies');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(Di::class, [
            \Quantum\Loader\Loader::class,
            \Quantum\Libraries\Storage\FileSystem::class,
        ]);

        $this->authService = (new ServiceFactory)->get(AuthService::class);

        $reflectionProperty = new \ReflectionProperty($this->authService, 'users');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->authService, []);

        $this->authService->add($this->initialUser);
    }

    public function testServiceGet()
    {
        $user = $this->authService->get('email', 'admin@qt.com');
        $this->assertIsArray($user);
        $this->assertArrayHasKey('password', $user);
        $this->assertArrayHasKey('firstname', $user);
        $this->assertArrayHasKey('lastname', $user);
        $this->assertArrayHasKey('role', $user);
        $this->assertArrayHasKey('remember_token', $user);
        $this->assertArrayHasKey('reset_token', $user);
        $this->assertEquals('admin', $user['role']);
    }

    public function testServiceAdd()
    {
        $user = $this->authService->add([
            'email' => 'guest@qt.com',
            'password' => '$2y$12$0M78WcmUZYQq85vHZLoNW.CyDUezRxh9Ye8/Z8oWCwJmBrz8p.j7C',
            'firstname' => 'Guest',
            'lastname' => 'User',
        ]);

        $this->assertIsArray($user);
        $this->assertArrayHasKey('email', $user);
        $this->assertEquals('guest@qt.com', $user['email']);
    }

    public function testServiceUpdate()
    {
        $user = $this->authService->get('email', 'admin@qt.com');

        $this->assertEmpty($user['remember_token']);

        $rememberToken = base64_encode(time());

        $this->authService->update('email', $user['email'], [
            'remember_token' => $rememberToken
        ]);

        $user = $this->authService->get('email', 'admin@qt.com');

        $this->assertNotEmpty($user['remember_token']);
        $this->assertEquals($user['remember_token'], $rememberToken);
    }

    public function testGetVisibleFields()
    {
        $this->assertIsArray($this->authService->getVisibleFields());
        $this->assertContains('role', $this->authService->getVisibleFields());
        $this->assertNotContains('password', $this->authService->getVisibleFields());
    }

    public function testGetDefinedKeys()
    {
        $this->assertIsArray($this->authService->getDefinedKeys());
        $this->assertArrayHasKey('passwordKey', $this->authService->getDefinedKeys());
        $this->assertArrayHasKey('rememberTokenKey', $this->authService->getDefinedKeys());
        $this->assertArrayHasKey('resetTokenKey', $this->authService->getDefinedKeys());
    }

}
