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
 * @since 2.0.0
 */

namespace Hooks;

use Quantum\Factory\ViewFactory;
use Quantum\Hooks\HookInterface;
use Quantum\Http\Response;

/**
 * Class ErrorPage
 * @package Hooks
 */
class ErrorPage implements HookInterface
{

    /**
     * Page not found
     *
     * @throws \Exception
     */
    public static function pageNotFound(Response $response)
    {
        $view = (new ViewFactory())->renderPartial('errors/404');
        $response->html($view);
        stop();
    }

}
