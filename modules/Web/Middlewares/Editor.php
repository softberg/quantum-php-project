<?php

namespace Modules\Web\Middlewares;

use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Editor extends Qt_Middleware
{

    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'editor') {
            redirect(base_url() . '/' . current_lang());
        }

        return $next($request, $response);
    }

}
