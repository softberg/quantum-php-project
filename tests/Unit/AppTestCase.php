<?php

namespace Quantum\Tests\Unit;

use Quantum\Service\Factories\ServiceFactory;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use PHPUnit\Framework\TestCase;

class AppTestCase extends TestCase
{

    protected $authService;

    protected $postService;

    public function setUp(): void
    {
        $this->authService = ServiceFactory::create(AuthService::class);

        $this->postService = ServiceFactory::create(PostService::class);
    }
}