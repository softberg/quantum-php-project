<?php

namespace Modules\Api\Middlewares;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Signout extends Qt_Middleware
{

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
