<?php

namespace Hooks;

use Quantum\Factory\ViewFactory;
use Quantum\Hooks\HookInterface;

class ErrorPage implements HookInterface
{

    /**
     * Page not found
     *
     * @return void
     * @throws RouteException When route not found
     */
    public static function pageNotFound()
    {
        $view = new ViewFactory();

        $view->output('errors/404');
        exit;
    }

}
