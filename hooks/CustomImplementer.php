<?php

namespace Hooks;

use Quantum\Http\Response;
use Quantum\Hooks\HookInterface;

class CustomImplementer implements HookInterface {

    public static function pageNotFound() {
        Response::errorPage('error404', 404, 'Front');
    }

}
