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
 * @since 2.6.0
 */

namespace Modules\Api\Middlewares;

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
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!auth()->check()) {
            $response->json([
                'status' => 'error',
                'message' => t('validation.unauthorizedRequest')
            ], 401);
            
            stop();
        }

        return $next($request, $response);
    }

}
