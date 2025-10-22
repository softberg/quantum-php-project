<?php

namespace Quantum\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Quantum\App\App;

class AppTestCase extends TestCase
{

    protected $defaultEmail = 'default@quantumphp.io';

    protected $defaultPassword = 'password';

    protected $firstname = 'John';

    protected $lastname = 'Doe';

    protected static $app;

    public static function setUpBeforeClass(): void
    {
        self::$app = createApp(App::WEB, PROJECT_ROOT);
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
        array  $params = [],
        array  $headers = [],
        array  $files = []
    ): Response
    {
        Request::create($method, $url, $params, $headers, $files);
        self::$app->start();
        return new Response();
    }

    protected function signInAndGetTokens(): array
    {
        $response = $this->request('post', '/api/en/signin', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword
        ]);

        return $response->get('tokens');
    }
}