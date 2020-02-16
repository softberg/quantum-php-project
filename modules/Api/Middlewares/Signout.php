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
 * Class Signout
 * @package Modules\Api\Middlewares
 */
class Signout extends Qt_Middleware
{
    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!Request::hasHeader('refresh_token')) {
            $response->json([
                'status' => 'error',
                'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]
            ]);
        }

        return $next($request, $response);
    }

}
