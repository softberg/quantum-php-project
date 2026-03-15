<?php

namespace Quantum\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Quantum\App\App;

class AppTestCase extends TestCase
{

    protected string $defaultEmail = 'default@quantumphp.io';

    protected string $defaultPassword = 'password';

    protected string $firstname = 'John';

    protected string $lastname = 'Doe';

    protected static App $app;

    public function setUp(): void
    {
        parent::setUp();
        ob_start();
        
        self::$app = createApp(App::WEB, PROJECT_ROOT);
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
        $response = $this->request('post', '/api/signin', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword
        ]);

        return $response->get('tokens');
    }
}