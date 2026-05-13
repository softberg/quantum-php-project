<?php

namespace Quantum\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Quantum\App\Enums\AppType;
use Quantum\Http\Response;
use Quantum\App\App;
use RuntimeException;

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

        $this->ensureRateLimitStorageExists();
        self::$app = createApp(AppType::WEB, PROJECT_ROOT);
        $this->clearRateLimitStorage();
    }

    public function tearDown(): void
    {
        $this->clearRateLimitStorage();
        parent::tearDown();
        ob_end_clean();
    }

    public function request(
        string $method,
        string $url,
        array  $params = [],
        array  $headers = [],
        array  $files = []
    ): Response {
        request()->create($method, $url, $params, $headers, $files);
        self::$app->start();
        return response();
    }

    protected function signInAndGetTokens(): array
    {
        $response = $this->request('post', '/api/signin', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
        ]);

        return $response->get('tokens');
    }

    private function clearRateLimitStorage(): void
    {
        $rateLimitDir = PROJECT_ROOT . DS . 'cache' . DS . 'data';

        if (!is_dir($rateLimitDir)) {
            return;
        }

        foreach (glob($rateLimitDir . DS . '*.rate') ?: [] as $file) {
            if (!unlink($file)) {
                throw new RuntimeException('Failed to remove rate limit file: ' . $file);
            }
        }

        foreach (glob($rateLimitDir . DS . '*.lock') ?: [] as $file) {
            if (!unlink($file)) {
                throw new RuntimeException('Failed to remove rate limit file: ' . $file);
            }
        }
    }

    private function ensureRateLimitStorageExists(): void
    {
        $rateLimitDir = PROJECT_ROOT . DS . 'cache' . DS . 'data';

        if (is_dir($rateLimitDir)) {
            return;
        }

        $created = mkdir($rateLimitDir, 0777, true);

        if (!$created && !is_dir($rateLimitDir)) {
            throw new RuntimeException('Failed to create rate limit storage directory: ' . $rateLimitDir);
        }
    }
}
