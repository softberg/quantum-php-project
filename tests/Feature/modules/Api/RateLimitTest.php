<?php

namespace Quantum\Tests\Feature\modules\Api;

use Quantum\Tests\Feature\AppTestCase;
use Quantum\Http\Response;

class RateLimitTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->clearRateLimitStorage();
    }

    public function tearDown(): void
    {
        $this->clearRateLimitStorage();
        parent::tearDown();
    }

    public function testSignInEndpointReturns429WhenLimitExceeded(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $response = $this->attemptFailedSignin();
            $this->assertNotSame(429, $response->getStatusCode());
        }

        $blocked = $this->attemptFailedSignin();

        $this->assertSame(429, $blocked->getStatusCode());
        $this->assertSame('10', $blocked->getHeader('X-RateLimit-Limit'));
        $this->assertSame('0', $blocked->getHeader('X-RateLimit-Remaining'));
        $this->assertNotNull($blocked->getHeader('Retry-After'));
        $this->assertSame('Too Many Requests', $blocked->get('message'));
    }

    public function testRateLimitBucketIsRouteSpecific(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->attemptFailedSignin();
        }

        $blocked = $this->attemptFailedSignin();
        $this->assertSame(429, $blocked->getStatusCode());

        response()->flush();

        $postsResponse = $this->request('get', '/api/en/posts');
        $this->assertSame('success', $postsResponse->get('status'));
        $this->assertNotSame(429, $postsResponse->getStatusCode());
    }

    private function attemptFailedSignin(): Response
    {
        response()->flush();

        return $this->request('post', '/api/en/signin', [
            'email' => 'non-existing-user@example.com',
            'password' => 'invalid-password',
        ]);
    }

    private function clearRateLimitStorage(): void
    {
        $rateLimitDir = PROJECT_ROOT . DS . 'cache' . DS . 'data';

        foreach (glob($rateLimitDir . DS . '*.rate') ?: [] as $file) {
            @unlink($file);
        }

        foreach (glob($rateLimitDir . DS . '*.lock') ?: [] as $file) {
            @unlink($file);
        }
    }

}
