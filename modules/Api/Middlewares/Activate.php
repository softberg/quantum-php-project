<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.8.0
 */

namespace Modules\Api\Middlewares;

use Quantum\Middleware\QtMiddleware;
use Quantum\Factory\ModelFactory;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Shared\Models\User;
use Closure;

/**
 * Class Activate
 * @package Modules\Api\Middlewares
 */
class Activate extends QtMiddleware
{

    /**
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        $token = route_param('token');

        if (!$token || !$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [t('validation.nonExistingRecord', 'token')]
            ]);

            stop();
        }

        $request->set('activation_token', $token);

        return $next($request, $response);
    }

    /**
     * Check token
     * @param string $token
     * @return bool
     */
    private function checkToken(string $token): bool
    {
        $userModel = ModelFactory::get(User::class);
        return !empty($userModel->findOneBy('activation_token', $token)->asArray());
    }

}
