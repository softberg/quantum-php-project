<?php

namespace Modules\Main\Middlewares;

use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Request;

class Editor extends Qt_Middleware {
    
    private $role = 'editor';
    
    public function apply(Request $request, \Closure $next) {
        if($request->get('role') != $this->role){
            redirect(base_url());
        }
        
        return $next($request);
    }
    
}
