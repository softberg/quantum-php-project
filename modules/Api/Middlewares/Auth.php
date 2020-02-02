<?php

namespace Modules\Api\Middlewares;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Auth extends Qt_Middleware
{

    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!auth()->check()) {
            $response->json([
                'status' => 'error',
                'message' => ExceptionMessages::UNAUTHORIZED_REQUEST
            ]);
        }

        return $next($request, $response);
    }

}
