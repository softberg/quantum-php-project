<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Model\Factories\ModelFactory;
use Quantum\Tests\Feature\AppTestCase;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Shared\Models\User;
use Faker\Factory;

class AuthControllerTest extends AppTestCase
{

    private $faker;

    public function setUp(): void
    {
        parent::setUp();

        Request::flush();
        Response::flush();

        $this->faker = Factory::create();
    }

    public function testModuleApiSignInEndpoint()
    {
        $response = $this->request('post', '/api/en/signin', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
        ]);

        $this->assertSuccess($response);

        $this->assertArrayHasKey('tokens', $response->all());
    }

    public function testModuleApiSignInEndpointWithIncorrectCredentials()
    {
        $response = $this->request('post', '/api/en/signin', [
            'email' => 'wrong@email.com',
            'password' => 'wrong-pass',
        ]);

        $this->assertError($response, 'Incorrect credentials');
    }

    public function testModuleApiMeEndpoint()
    {
        $tokens = $this->signInAndGetTokens();

        $response = $this->request('get', '/api/en/me', [], [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
        ]);

        $this->assertSuccess($response);

        $this->assertArrayHasKey('data', $response->all());
        $this->assertArrayHasKey('firstname', $response->get('data'));
        $this->assertArrayHasKey('lastname', $response->get('data'));
        $this->assertArrayHasKey('email', $response->get('data'));
    }

    public function testModuleApiMeEndpointUnAuthenticated()
    {
        $response = $this->request('get', '/api/en/me');

        $this->assertError($response, 'Unauthorized request');
    }

    public function testModuleApiSignoutEndpoint()
    {
        $tokens = $this->signInAndGetTokens();

        $response = $this->request('get', '/api/en/signout', [], [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
        ]);

        $this->assertSuccess($response);
    }

    public function testModuleApiSignupEndpoint()
    {
        $email = $this->faker->email();
        $firstname = $this->faker->firstName();
        $lastname = $this->faker->lastName();

        $response = $this->request('post', '/api/en/signup', [
            'email' => $email,
            'password' => $this->defaultPassword,
            'firstname' => $firstname,
            'lastname' => $lastname,
        ]);

        $this->assertSuccess($response, 'Successfully signed up');

        $user = ModelFactory::get(User::class)->findOneBy('email', $email);

        $this->assertEquals($firstname, $user->firstname);
        $this->assertEquals($lastname, $user->lastname);

        deleteUserByEmail($email);
    }

    public function testModuleApiSignupEndpointWithExistingEmail()
    {
        $response = $this->request('post', '/api/en/signup', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals(
            'The Email field should contain only unique value',
            $response->get('message')['email'][0]
        );
    }

    public function testModuleApiActivateEndpoint()
    {
        $token = $this->faker->uuid();
        $email = $this->faker->email();

        createUser(['email' => $email, 'activation_token' => $token]);

        $response = $this->request('get', '/api/en/activate/' . $token);

        $this->assertSuccess($response, 'Account is activated');
        deleteUserByEmail($email);
    }

    public function testModuleApiActivateEndpointWithInvalidToken()
    {
        $response = $this->request('get', '/api/en/activate/invalid-token');

        $this->assertEquals('error', $response->get('status'));
        $this->assertEquals(
            'The Token does not exist in our records',
            $response->get('message')['token'][0]
        );
    }

    public function testModuleApiForgetEndpoint()
    {
        $response = $this->request('post', '/api/en/forget', ['email' => $this->defaultEmail]);

        $this->assertSuccess($response, 'Check your email to reset your password');
    }

    public function testModuleApiResetEndpoint()
    {
        $token = $this->faker->uuid();
        $email = $this->faker->email();

        createUser(['email' => $email, 'reset_token' => $token]);

        $response = $this->request('post', '/api/en/reset/' . $token, [
            'password' => $this->defaultPassword,
            'repeat_password' => $this->defaultPassword,
        ]);

        $this->assertSuccess($response);
        deleteUserByEmail($email);
    }

    public function testModuleApiResetEndpointWithIncorrectToken()
    {
        $response = $this->request('post', '/api/en/reset/invalid-token', [
            'password' => $this->defaultPassword,
            'repeat_password' => $this->defaultPassword,
        ]);

        $this->assertError($response, 'The Token does not exist in our records');
    }

    public function testModuleApiVerifyEndpoint()
    {
        $email = $this->faker->email();
        $otp   = $this->faker->uuid();

        $user  = createUser([
            'email'     => $email,
            'otp_token' => base64_encode($otp),
            'otp'       => $otp,
        ]);

        $response = $this->request('post', '/api/en/verify', [
            'otp'  => $otp,
            'code' => base64_encode($otp),
        ]);

        $this->assertSuccess($response);

        $this->assertArrayHasKey('tokens', $response->all());
        $this->assertArrayHasKey('access_token', $response->get('tokens'));
        $this->assertArrayHasKey('refresh_token', $response->get('tokens'));

        deleteUserByEmail($email);
    }

    public function testModuleApiVerifyEndpointWithIncorrectOtp()
    {
        $response = $this->request('post', '/api/en/verify', [
            'otp'  => 'wrong-otp',
            'code' => 'wrong-code',
        ]);

        $this->assertError($response, 'Incorrect verification code.');
    }

    public function testModuleApiResendEndpoint()
    {
        $email = $this->faker->email();
        $token = base64_encode($this->faker->uuid());

        createUser(['email' => $email, 'otp_token' => $token]);

        $response = $this->request('get', '/api/en/resend/' . $token);

        $this->assertSuccess($response);

        $this->assertIsString($response->get('code'));

        deleteUserByEmail($email);
    }

    public function testModuleApiResendEndpointWithIncorrectOtpToken()
    {
        $response = $this->request('get', '/api/en/resend/invalid-token');

        $this->assertError($response, 'Incorrect credentials');
    }

    protected function assertSuccess($response, string $message = null): void
    {
        $this->assertEquals('success', $response->get('status'));
        $this->assertEquals($message, $response->get('message'));
    }

    protected function assertError($response, $expected)
    {
        $this->assertEquals('error', $response->get('status'));

        $actual = $response->get('message');

        if (is_array($actual)) {
            $messages = [];
            foreach ($actual as $field => $msgs) {
                $messages[] = is_array($msgs) ? implode(', ', $msgs) : $msgs;
            }
            $actual = implode(' | ', $messages);
        }

        $this->assertStringContainsString($expected, $actual);
    }
}