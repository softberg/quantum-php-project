<?php

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Factory\ServiceFactory;
use Shared\Services\AuthService;
use PHPUnit\Framework\TestCase;
use Quantum\Di\Di;
use Quantum\App;

class AuthServiceTest extends TestCase
{

    public $authService;

    private $initialUser = [
        'email' => 'anonymous@qt.com',
        'password' => '$2y$12$4Y4/1a4308KEiGX/xo6vgO41szJuDHC7KhpG5nknx/xxnLZmvMyGi',
        'firstname' => 'Tom',
        'lastname' => 'Hunter',
        'role' => 'admin',
        'activation_token' => '',
        'remember_token' => '',
        'reset_token' => '',
        'access_token' => '',
        'refresh_token' => '',
        'otp' => '',
        'otp_expires' => '',
        'otp_token' => '',
    ];

    public function setUp(): void
    {
        App::loadCoreFunctions(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'Helpers');

        App::setBaseDir(__DIR__ . DS . '_root');

        Di::loadDefinitions();

        $this->authService = ServiceFactory::get(AuthService::class, ['shared' . DS . 'store', 'users']);

        $this->authService->add($this->initialUser);
    }

    public function tearDown(): void
    {
        $this->authService->deleteTable();
        $this->removeFolders();
    }

    public function testUserGet()
    {
        $user = $this->authService->get('email', 'anonymous@qt.com');

        $this->assertInstanceOf(\Quantum\Libraries\Auth\User::class, $user);
        $this->assertArrayHasKey('password', $user->getData());
        $this->assertArrayHasKey('firstname', $user->getData());
        $this->assertArrayHasKey('lastname', $user->getData());
        $this->assertArrayHasKey('role', $user->getData());
        $this->assertArrayHasKey('remember_token', $user->getData());
        $this->assertArrayHasKey('reset_token', $user->getData());
        $this->assertEquals('admin', $user->getFieldValue('role'));
    }

    public function testUserGetById()
    {
        $user = $this->authService->get('id', 1);

        $this->assertInstanceOf(\Quantum\Libraries\Auth\User::class, $user);

        $userData = $user->getData();

        $this->assertIsArray($userData);
    }

    public function testUserAdd()
    {
        $user = $this->authService->add([
            'email' => 'guest@qt.com',
            'password' => '$2y$12$0M78WcmUZYQq85vHZLoNW.CyDUezRxh9Ye8/Z8oWCwJmBrz8p.j7C',
            'firstname' => 'Guest',
            'lastname' => 'User',
        ]);

        $this->assertInstanceOf(\Quantum\Libraries\Auth\User::class, $user);
        $this->assertArrayHasKey('email', $user->getData());
        $this->assertEquals('guest@qt.com', $user->getFieldValue('email'));
    }

    public function testUserUpdate()
    {
        $user = $this->authService->get('email', 'anonymous@qt.com');

        $this->assertEmpty($user->getFieldValue('remember_token'));

        $rememberToken = base64_encode(time());

        $this->authService->update(
            'email',
            $user->getFieldValue('email'),
            ['remember_token' => $rememberToken]
        );

        $user = $this->authService->get('email', 'anonymous@qt.com');

        $this->assertNotEmpty($user->getFieldValue('remember_token'));
        $this->assertEquals($user->getFieldValue('remember_token'), $rememberToken);
    }

    public function testUserSchema()
    {
        $this->assertIsArray($this->authService->userSchema());
        $this->assertArrayHasKey('username', $this->authService->userSchema());
        $this->assertArrayHasKey('password', $this->authService->userSchema());
        $this->assertArrayHasKey('activationToken', $this->authService->userSchema());
        $this->assertArrayHasKey('rememberToken', $this->authService->userSchema());
        $this->assertArrayHasKey('resetToken', $this->authService->userSchema());
        $this->assertArrayHasKey('accessToken', $this->authService->userSchema());
        $this->assertArrayHasKey('refreshToken', $this->authService->userSchema());
        $this->assertArrayHasKey('otp', $this->authService->userSchema());
        $this->assertArrayHasKey('otpExpiry', $this->authService->userSchema());
        $this->assertArrayHasKey('otpToken', $this->authService->userSchema());
        $this->assertIsArray($this->authService->userSchema()['username']);
        $this->assertArrayHasKey('name', $this->authService->userSchema()['username']);
        $this->assertArrayHasKey('visible', $this->authService->userSchema()['username']);
    }

    private function removeFolders()
    {
        $fs = Di::get(FileSystem::class);

        $uploadsFolder = $fs->glob(uploads_dir() . DS . '*');

        foreach ($uploadsFolder as $user_uuid) {
            $fs->removeDirectory($user_uuid);
        }
    }
}
