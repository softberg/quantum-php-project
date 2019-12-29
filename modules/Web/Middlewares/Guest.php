<?php

namespace Modules\Web\Middlewares;

use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Guest extends Qt_Middleware
{

    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (auth()->check()) {
            redirect(get_referrer() ?? base_url() . '/' . current_lang());
        }

        return $next($request, $response);

    }

}
