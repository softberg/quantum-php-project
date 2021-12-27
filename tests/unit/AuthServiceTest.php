<?php

use PHPUnit\Framework\TestCase;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Factory\ServiceFactory;
use Base\Services\AuthService;
use Quantum\Di\Di;
use Quantum\App;
use Quantum\Loader\Loader;

class AuthServiceTest extends TestCase
{

    public $authService;

    private $userRepository;

    private $initialUser = [
        'id' => 1,
        'email' => 'admin@qt.com',
        'password' => '$2y$12$4Y4/1a4308KEiGX/xo6vgO41szJuDHC7KhpG5nknx/xxnLZmvMyGi',
        'firstname' => 'Admin',
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

        Di::add(\Quantum\Loader\Setup::class);

        $loader = Di::get(Loader::class);

        $loader->loadDir(dirname(__DIR__, 2) . DS . 'helpers');

        $this->userRepository = base_dir() . DS . 'base' . DS . 'store' . DS . 'users.php';

        $fs = new FileSystem();

        if(!$fs->exists($this->userRepository)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . export([]) . ';';
            $fs->put($this->userRepository, $content);
        }

        $this->authService = (new ServiceFactory)->get(AuthService::class, ['base' . DS . 'store', 'users']);

        $this->authService->add($this->initialUser);
    }

    public function tearDown(): void
    {
        $fs = new FileSystem();

        if($fs->exists($this->userRepository)) {
            $fs->remove($this->userRepository);
        }
    }

    public function testServiceGet()
    {
        $user = $this->authService->get('email', 'admin@qt.com');
        $this->assertInstanceOf(\Quantum\Libraries\Auth\User::class, $user);
        $this->assertArrayHasKey('password', $user->getData());
        $this->assertArrayHasKey('firstname', $user->getData());
        $this->assertArrayHasKey('lastname', $user->getData());
        $this->assertArrayHasKey('role', $user->getData());
        $this->assertArrayHasKey('remember_token', $user->getData());
        $this->assertArrayHasKey('reset_token', $user->getData());
        $this->assertEquals('admin', $user->getFieldValue('role'));
    }

    public function testServiceAdd()
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

    public function testServiceUpdate()
    {
        $user = $this->authService->get('email', 'admin@qt.com');

        $this->assertEmpty($user->getFieldValue('remember_token'));

        $rememberToken = base64_encode(time());

        $this->authService->update('email', $user->getFieldValue('email'),
            ['remember_token' => $rememberToken]
        );

        $user = $this->authService->get('email', 'admin@qt.com');

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

}
