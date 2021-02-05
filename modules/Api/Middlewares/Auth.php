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
 * @since 2.0.0
 */

namespace Modules\Api\Middlewares;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Auth
 * @package Modules\Api\Middlewares
 */
class Auth extends QtMiddleware
{
    
    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!auth()->check()) {
            $response->json([
                'status' => 'error',
                'message' => ExceptionMessages::UNAUTHORIZED_REQUEST
            ]);
            
            stop();
        }

        if (auth()->checkVerification() && (current_controller() != 'AuthController' && current_action() != 'verify')) {

            $response->json([
                'status' => 'error',
                'message' => 'Please verify your account.'
            ]);

            stop();
        }
        return $next($request, $response);
    }

}
