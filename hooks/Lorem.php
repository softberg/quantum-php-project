<?php

namespace Hooks;

use Quantum\Http\Response;
use Quantum\Hooks\HookInterface;

class Lorem implements HookInterface {

    public static function methodOptionHeaders() {
        
        Response::setHeader('Access-Control-Allow-Origin', '*');
        Response::setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        Response::setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }
    
    public static function xxpageNotFound() {
        echo "Page not found";
    }

}
