<?php

namespace Quantum\Tests\Feature\Api;

use Quantum\Tests\Feature\BaseTestCase;
use Quantum\Libraries\Hasher\Hasher;
use Quantum\Factory\ModelFactory;
use Quantum\Router\Router;
use Shared\Models\User;

class AuthControllerTest extends BaseTestCase
{
	/**
	 * @var string
	 */
	protected $email = 'test@test.test';

	/**
	 * @var string
	 */
	protected $password = 'password';

	public function setUp(): void
	{
		parent::setUp();
	}

	public function testSignInApi()
	{
		$response = $this->request('post', '/api/en/signin', [
			'email' => $this->email,
			'password' => $this->password
		]);

		$this->assertIsObject($response);
		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('tokens', $response->all());
	}

	public function testSignInWithIncorrectRequestApi()
	{
		$response = $this->request('post', '/api/en/signin', [
			'email' => 'test@email.com',
			'password' => 'password'
		]);

		$this->assertIsObject($response);
		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('Incorrect credentials', $response->get('message'));
	}

	public function testMeApi()
	{
		Router::setCurrentRoute([
			'module' => 'Api',
		]);
		$tokens = auth()->signin($this->email, $this->password);

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

	public function testMeIncorrectApi()
	{
		$response = $this->request('get', '/api/en/me');

		$this->assertIsObject($response);
		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('Unauthorized request', $response->get('message'));
	}

	public function testSignoutApi()
	{
		Router::setCurrentRoute([
			'module' => 'Api',
		]);
		$tokens = auth()->signin($this->email, $this->password);

		$response = $this->request('get', '/api/en/signout', [], [
			'Authorization' => 'Bearer ' . $tokens['access_token'],
			'refresh_token' => $tokens['refresh_token']
		]);

		$this->assertEquals('success', $response->get('status'));
	}

	public function testForgetApi()
	{
		$response = $this->request('post', '/api/en/forget', ['email' => $this->email]);

		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Check your email to reset your password', $response->get('message'));
	}

	public function testSignupApi()
	{
		$response = $this->request('post', '/api/en/signup', [
			'email' => 'test@email.com',
			'password' => $this->password,
			'firstname' => 'firstname',
			'lastname' => 'lastname'
		]);

		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Successfully signed up', $response->get('message'));

		ModelFactory::get(User::class)->findOneBy('email', 'test@email.com')->delete();
	}

	public function testSignupIncorrectApi()
	{
		$response = $this->request('post', '/api/en/signup', [
			'email' => $this->email,
			'password' => $this->password,
			'firstname' => 'firstname',
			'lastname' => 'lastname'
		]);

		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('The value of Email field is already exists in our database', $response->get('message')['email'][0]);
	}

	public function testActivateApi()
	{
		$userModel = ModelFactory::get(User::class)->create();
		$activationToken = base64_encode((new Hasher())->hash('password'));
		$userModel->fillObjectProps([
			'email' => $this->email,
			'password' => (new Hasher())->hash($this->password),
			'firstname' => 'firstname',
			'lastname' => 'lastname',
			'activation_token' => $activationToken
		]);
		$userModel->save();

		$response = $this->request('get', '/api/en/activate/' . $activationToken);

		$this->assertEquals('success', $response->get('status'));
		$this->assertEquals('Account is activated', $response->get('message'));
	}

	public function testActivateIncorrectApi()
	{
		$response = $this->request('get', '/api/en/activate/incorrect-activation-token');

		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('There is no record matched to token', $response->get('message')[0]);
	}

	public function testResetApi()
	{
		$userModel = ModelFactory::get(User::class)->create();
		$resetToken = base64_encode((new Hasher())->hash('password'));
		$userModel->fillObjectProps([
			'email' => $this->email,
			'password' => (new Hasher())->hash($this->password),
			'firstname' => 'firstname',
			'lastname' => 'lastname',
			'reset_token' => $resetToken
		]);
		$userModel->save();

		$response = $this->request('post', '/api/en/reset/' . $resetToken, [
			'password' => $this->password,
			'repeat_password' => $this->password,
		]);

		$this->assertEquals('success', $response->get('status'));
	}

	public function testResetIncorrectApi()
	{
		$response = $this->request('post', '/api/en/reset/incorrect-activation-token');

		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('There is no record matched to token', $response->get('message')[0]);
	}

	public function testResendApi()
	{

		$userModel = ModelFactory::get(User::class)->create();
		$otpToken = base64_encode((new Hasher())->hash($this->email));
		$userModel->fillObjectProps([
			'email' => $this->email,
			'password' => (new Hasher())->hash($this->password),
			'firstname' => 'firstname',
			'lastname' => 'lastname',
			'otp_token' => $otpToken
		]);
		$userModel->save();
		$response = $this->request('get', '/api/en/resend/' . $otpToken);

		$this->assertEquals('success', $response->get('status'));
		$this->assertArrayHasKey('code', $response->all());
		$this->assertIsString($response->get('code'));
	}

	public function testResendIncorrectApi()
	{
		$response = $this->request('get', '/api/en/resend/incorrect-otp-token');

		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('Incorrect credentials', $response->get('message'));
	}

	public function testVerifyApi()
	{
		$userModel = ModelFactory::get(User::class)->create();
		$otpToken = base64_encode((new Hasher())->hash($this->email));
		$userModel->fillObjectProps([
			'email' => $this->email,
			'password' => (new Hasher())->hash($this->password),
			'firstname' => 'firstname',
			'lastname' => 'lastname',
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

	public function testVerifyIncorrectApi()
	{
		$response = $this->request('post', '/api/en/verify', [
			'otp' => 'incorrect-otp',
			'code' => 'incorrect-code',
		]);

		$this->assertEquals('error', $response->get('status'));
		$this->assertEquals('Incorrect verification code.', $response->get('message'));
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}
}