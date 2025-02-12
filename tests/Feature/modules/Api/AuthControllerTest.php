<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Tests\Feature\AppTestCase;
use Quantum\Libraries\Hasher\Hasher;
use Quantum\Factory\ModelFactory;
use Quantum\Http\Request;
use Shared\Models\User;

class AuthControllerTest extends AppTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        Request::flush();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testModuleApiSignInEndpoint()
    {
        $response = $this->request('post', '/api/en/signin', [
            'email' => self::$defaultEmail,
            'password' => self::$defaultPassword
        ]);

        $this->assertIsObject($response);
        $this->assertEquals('success', $response->get('status'));
        $this->assertArrayHasKey('tokens', $response->all());
    }

    public function testModuleApiSignInEndpointWithIncorrectCredentials()
    {
        $response = $this->request('post', '/api/en/signin', [
            'email' => 'test@email.com',
            'password' => 'pass'
        ]);

        $this->assertIsObject($response);
        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('Incorrect credentials', $response->get('message'));
    }

    public function testModuleApiMeEndpoint()
    {
        $tokens = $this->signInAndGetTokens();

        $response = $this->request('get', '/api/en/me', [], ['Authorization' => 'Bearer ' . $tokens['access_token']]);

        $this->assertIsObject($response);
        $this->assertEquals('success', $response->get('status'));
        $this->assertIsArray($response->all());
        $this->assertArrayHasKey('data', $response->all());
        $this->assertIsArray($response->get('data'));
        $this->assertArrayHasKey('firstname', $response->get('data'));
        $this->assertArrayHasKey('lastname', $response->get('data'));
        $this->assertArrayHasKey('email', $response->get('data'));
    }

    public function testModuleApiMeEndpointUnAuthenticated()
    {
        $response = $this->request('get', '/api/en/me');

        $this->assertIsObject($response);
        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('Unauthorized request', $response->get('message'));
    }

    public function testModuleApiSignoutEndpoint()
    {
        $tokens = $this->signInAndGetTokens();

        $response = $this->request('get', '/api/en/signout', [], [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token']
        ]);

        $this->assertEquals('success', $response->get('status'));
    }

    public function testModuleApiSignupEndpoint()
    {
        $response = $this->request('post', '/api/en/signup', [
            'email' => 'john@doe.com',
            'password' => self::$defaultPassword,
            'firstname' => 'John',
            'lastname' => 'Doe'
        ]);

        $this->assertEquals('success', $response->get('status'));
        $this->assertEquals('Successfully signed up', $response->get('message'));

        ModelFactory::get(User::class)->findOneBy('email', 'john@doe.com')->delete();
    }

    public function testModuleApiSignupEndpointWithInvalidData()
    {
        $response = $this->request('post', '/api/en/signup', [
            'email' => self::$defaultEmail,
            'password' => self::$defaultPassword,
            'firstname' => 'John',
            'lastname' => 'Doe'
        ]);

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('The value of Email field is already exists in our database', $response->get('message')['email'][0]);
    }

    public function testModuleApiActivateEndpoint()
    {
        $userModel = ModelFactory::get(User::class)->create();
        $activationToken = base64_encode((new Hasher())->hash(self::$defaultPassword));

        $userModel->fillObjectProps([
            'email' => self::$defaultEmail,
            'password' => (new Hasher())->hash(self::$defaultPassword),
            'firstname' => 'John',
            'lastname' => 'Doe',
            'activation_token' => $activationToken
        ]);

        $userModel->save();

        $response = $this->request('get', '/api/en/activate/' . $activationToken);

        $this->assertEquals('success', $response->get('status'));
        $this->assertEquals('Account is activated', $response->get('message'));
    }

    public function testModuleApiActivateEndpointWithInvalidToken()
    {
        $response = $this->request('get', '/api/en/activate/incorrect-activation-token');

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('There is no record matched to the token', $response->get('message')[0]);
    }

    public function testModuleApiForgetEndpoint()
    {
        $response = $this->request('post', '/api/en/forget', ['email' => self::$defaultEmail]);

        $this->assertEquals('success', $response->get('status'));
        $this->assertEquals('Check your email to reset your password', $response->get('message'));
    }

    public function testModuleApiResetEndpoint()
    {
        $userModel = ModelFactory::get(User::class)->create();
        $resetToken = base64_encode((new Hasher())->hash(self::$defaultPassword));

        $userModel->fillObjectProps([
            'email' => self::$defaultEmail,
            'password' => (new Hasher())->hash(self::$defaultPassword),
            'firstname' => 'John',
            'lastname' => 'Doe',
            'reset_token' => $resetToken
        ]);

        $userModel->save();

        $response = $this->request('post', '/api/en/reset/' . $resetToken, [
            'password' => self::$defaultPassword,
            'repeat_password' => self::$defaultPassword,
        ]);

        $this->assertEquals('success', $response->get('status'));
    }

    public function testModuleApiResetEndpointWithIncorrectToken()
    {
        $response = $this->request('post', '/api/en/reset/incorrect-reset-token');

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('There is no record matched to the token', $response->get('message')[0]);
    }

    public function testModuleApiVerifyEndpoint()
    {
        $userModel = ModelFactory::get(User::class)->create();
        $otpToken = base64_encode((new Hasher())->hash(self::$defaultEmail));

        $userModel->fillObjectProps([
            'email' => self::$defaultEmail,
            'password' => (new Hasher())->hash(self::$defaultPassword),
            'firstname' => 'John',
            'lastname' => 'Doe',
            'otp_token' => $otpToken,
            'otp' => $otpToken
        ]);
        $userModel->save();

        $response = $this->request('post', '/api/en/verify', [
            'otp' => $otpToken,
            'code' => $otpToken,
        ]);

        $this->assertEquals('success', $response->get('status'));
        $this->assertArrayHasKey('tokens', $response->all());
        $this->assertArrayHasKey('refresh_token', $response->get('tokens'));
        $this->assertArrayHasKey('access_token', $response->get('tokens'));
    }

    public function testModuleApiVerifyEndpointWithIncorrectOtp()
    {
        $response = $this->request('post', '/api/en/verify', [
            'otp' => 'incorrect-otp',
            'code' => 'incorrect-code',
        ]);

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('Incorrect verification code.', $response->get('message'));
    }

    public function testModuleApiResendEndpoint()
    {
        $userModel = ModelFactory::get(User::class)->create();
        $otpToken = base64_encode((new Hasher())->hash(self::$defaultEmail));

        $userModel->fillObjectProps([
            'email' => self::$defaultEmail,
            'password' => (new Hasher())->hash(self::$defaultPassword),
            'firstname' => 'John',
            'lastname' => 'Doe',
            'otp_token' => $otpToken
        ]);

        $userModel->save();
        $response = $this->request('get', '/api/en/resend/' . $otpToken);

        $this->assertEquals('success', $response->get('status'));
        $this->assertArrayHasKey('code', $response->all());
        $this->assertIsString($response->get('code'));
    }

    public function testModuleApiResendEndpointWithIncorrectOtpToken()
    {
        $response = $this->request('get', '/api/en/resend/incorrect-otp-token');

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals('Incorrect credentials', $response->get('message'));
    }
}