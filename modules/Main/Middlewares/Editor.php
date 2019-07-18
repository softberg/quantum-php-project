<?php

namespace Modules\Main\Middlewares;

use Modules\Main\Models\User;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Request;

class Editor extends Qt_Middleware {

    public function apply(Request $request, \Closure $next) {
        if(!User::isEditor()){
            redirect(base_url());
        }
        
        return $next($request);
    }
    
}
