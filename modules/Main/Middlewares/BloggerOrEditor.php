<?php

namespace Modules\Main\Middlewares;

use Modules\Main\Models\User;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class BloggerOrEditor extends Qt_Middleware {

    public function apply(Request $request, Response $response, \Closure $next) {
        if(!User::isEditor() && !User::isBlogger() ){
            redirect(base_url());
        }

        return $next($request, $response);
    }

}
