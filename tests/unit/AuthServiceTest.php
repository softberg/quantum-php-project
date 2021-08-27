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
    private $userRepository = BASE_DIR . DS . 'base' . DS . 'repositories' . DS . 'users.php';
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

    /**
     * @throws \ReflectionException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \Quantum\Exceptions\ServiceException
     */
    public function setUp(): void
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $fs = new FileSystem();

        $loader = new Loader($fs);

        $loader->loadFile(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'constants.php');

        $loader->loadDir(HELPERS_DIR . DS . 'functions');

        $loader->loadDir(BASE_DIR . DS . 'helpers');

        Di::loadDefinitions();


        if(!$fs->exists($this->userRepository)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . export([]) . ';';
            $fs->put($this->userRepository, $content);
            sleep(5);
            dump("Created: ". $this->userRepository);
        }

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

    public function tearDown(): void
    {
//        $fs = new FileSystem();
//        $fs->remove($this->userRepository);
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
