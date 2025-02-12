<?php

namespace Quantum\Tests\Feature;

use Quantum\Libraries\Storage\Factories\FileSystemFactory;
use Quantum\Libraries\Auth\User as AuthUser;
use Quantum\Libraries\Hasher\Hasher;
use Quantum\Factory\ServiceFactory;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use PHPUnit\Framework\TestCase;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Faker\Factory;

class AppTestCase extends TestCase
{

    const POST_COUNT_PER_USER = 10;

    protected static $app;

    protected static $faker;

    protected static $fs;

    protected static $defaultRole = 'editor';

    protected static $defaultEmail = 'tester@quantumphp.io';

    protected static $defaultPassword = 'password';

    protected static $authService;

    protected static $postService;

    protected $baseUrl;

    public static function setUpBeforeClass(): void
    {
        self::$fs = FileSystemFactory::get();

        self::$faker = Factory::create();

        self::$app = $GLOBALS['app'];

        self::$authService = ServiceFactory::get(AuthService::class);

        self::$postService = ServiceFactory::get(PostService::class);

        $user = self::createUser();

        self::createUserPosts($user->uuid);
    }

    public static function tearDownAfterClass(): void
    {
        self::cleanUp();
    }

    public function setUp(): void
    {
        parent::setUp();
        ob_start();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        ob_end_clean();
    }

    public function request(
        string $method,
        string $url,
        array  $options = [],
        array  $headers = [],
        string $contentType = 'application/x-www-form-urlencoded'
    ): Response
    {
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
        $_SERVER['CONTENT_TYPE'] = $contentType;
        $_SERVER['REQUEST_URI'] = $url;

        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                $_SERVER['HTTP_' . strtoupper($name)] = $value;
            }
        }

        Request::create(strtoupper($method), $url, $options);

        self::$app->start();

        return new Response();
    }

    protected function signInAndGetTokens(): array
    {
        $response = $this->request('post', '/api/en/signin', [
            'email' => self::$defaultEmail,
            'password' => self::$defaultPassword
        ]);

        return $response->get('tokens');
    }

    protected static function createUser(): AuthUser
    {
        return self::$authService->add([
            'firstname' => self::$faker->firstName,
            'lastname' => self::$faker->lastName,
            'role' => self::$defaultRole,
            'email' => self::$defaultEmail,
            'password' => (new Hasher())->hash(self::$defaultPassword),
        ]);
    }

    protected static function createUserPosts(string $userUuid)
    {
        $user = self::$authService->getUserByUuid($userUuid);

        for ($i = 0; $i < self::POST_COUNT_PER_USER; $i++) {
            $title = str_replace(['"', '\'', '-'], '', self::$faker->realText(50));

            self::$postService->addPost([
                'title' => $title,
                'content' => str_replace(['"', '\'', '-'], '', self::$faker->realText(100)),
                'image' => slugify($title) . '.jpg',
                'user_id' => $user->id,
            ]);
        }
    }

    protected static function cleanUp()
    {
        self::$authService->deleteTable();
        self::$postService->deleteTable();
    }
}