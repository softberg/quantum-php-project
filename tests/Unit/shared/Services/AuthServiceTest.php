<?php

namespace Quantum\Tests\Unit\shared\Services;

use Quantum\Libraries\Auth\User as AuthUser;
use Quantum\Tests\Unit\AppTestCase;
use Quantum\Model\ModelCollection;
use Shared\Models\User;

class AuthServiceTest extends AppTestCase
{

    public $authService;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testAuthServiceGetAll()
    {
        $users = $this->authService->getAll();

        $this->assertInstanceOf(ModelCollection::class, $users);

        $this->assertInstanceOf(User::class, $users->first());

        $this->assertEquals('tester@quantumphp.io', $users->first()->email);
    }

    public function testAuthServiceGetUserByUuid()
    {
        $users = $this->authService->getAll();

        $user = $this->authService->getUserByUuid($users->first()->uuid);

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals('tester@quantumphp.io', $user->email);
    }

    public function testAuthServiceGet()
    {
        $user = $this->authService->get('email', 'tester@quantumphp.io');

        $this->assertInstanceOf(AuthUser::class, $user);

        $userData = $user->getData();

        $this->assertIsArray($userData);

        $this->assertArrayHasKey('password', $userData);

        $this->assertArrayHasKey('firstname', $userData);

        $this->assertArrayHasKey('lastname', $userData);

        $this->assertArrayHasKey('role', $userData);

        $this->assertEquals('editor', $user->getFieldValue('role'));
    }

    public function testAuthServiceAdd()
    {
        $user = $this->authService->add([
            'email' => 'guest@qt.com',
            'password' => '$2y$12$0M78WcmUZYQq85vHZLoNW.CyDUezRxh9Ye8/Z8oWCwJmBrz8p.j7C',
            'firstname' => 'Guest',
            'lastname' => 'User',
        ]);

        $this->assertInstanceOf(AuthUser::class, $user);

        $this->assertArrayHasKey('email', $user->getData());

        $this->assertEquals('guest@qt.com', $user->getFieldValue('email'));
    }

    public function testAuthServiceUpdate()
    {
        $user = $this->authService->get('email', 'tester@quantumphp.io');

        $this->assertEmpty($user->getFieldValue('remember_token'));

        $rememberToken = base64_encode(time());

        $this->authService->update(
            'email',
            $user->getFieldValue('email'),
            ['remember_token' => $rememberToken]
        );

        $user = $this->authService->get('email', 'tester@quantumphp.io');

        $this->assertNotEmpty($user->getFieldValue('remember_token'));

        $this->assertEquals($user->getFieldValue('remember_token'), $rememberToken);
    }

    public function testAuthServiceUserSchema()
    {
        $userSchema = $this->authService->userSchema();

        $this->assertIsArray($userSchema);

        $this->assertArrayHasKey('username', $userSchema);
        $this->assertArrayHasKey('password', $userSchema);
        $this->assertArrayHasKey('activationToken', $userSchema);
        $this->assertArrayHasKey('rememberToken', $userSchema);
        $this->assertArrayHasKey('resetToken', $userSchema);
        $this->assertArrayHasKey('accessToken', $userSchema);
        $this->assertArrayHasKey('refreshToken', $userSchema);
        $this->assertArrayHasKey('otp', $userSchema);
        $this->assertArrayHasKey('otpExpiry', $userSchema);
        $this->assertArrayHasKey('otpToken', $userSchema);

        $this->assertIsArray($userSchema['username']);
        $this->assertArrayHasKey('name', $userSchema['username']);
        $this->assertArrayHasKey('visible', $userSchema['username']);
    }
}