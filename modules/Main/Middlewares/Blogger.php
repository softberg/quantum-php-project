<?php

namespace Modules\Main\Middlewares;

use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Request;

class Blogger extends Qt_Middleware {
    
    private $role = 'blogger';

    public function apply(Request $request, \Closure $next) {
        if($request->get('role') != $this->role && $request->get('role') != 'editor'){
            redirect(base_url());
        }
        
        return $next($request);
    }

}
