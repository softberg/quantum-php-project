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
 * @since 1.9.9
 */

namespace Modules\Api\Middlewares;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Editor
 * @package Modules\Api\Middlewares
 */
class Editor extends Qt_Middleware
{

    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'editor') {
            $response->json([
                'status' => 'error',
                'message' => ExceptionMessages::UNAUTHORIZED_REQUEST
            ]);
        }

        return $next($request, $response);
    }

}
