<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.9.9
 */

namespace Hooks;

use Quantum\Factory\ViewFactory;
use Quantum\Hooks\HookInterface;

/**
 * Class ErrorPage
 * @package Hooks
 */
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
