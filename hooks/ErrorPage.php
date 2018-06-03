<?php

namespace Hooks;

use Quantum\Hooks\HookInterface;
use Quantum\Http\Response;

class ErrorPage implements HookInterface {

    /**
     * Page not found
     * 
     * @return void
     * @throws RouteException When route not found
     */
    public static function pageNotFound() {
        $qt = qt_instance();
        $qt->output('errors/error_404', 'Main');
        exit;
    }

}
