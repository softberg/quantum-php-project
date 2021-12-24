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
 * @since 2.6.0
 */

namespace Hooks;

use Quantum\Factory\ViewFactory;
use Quantum\Hooks\HookInterface;
use Quantum\Http\Response;
use Quantum\Di\Di;

/**
 * Class ErrorPage
 * @package Hooks
 */
class ErrorPage implements HookInterface
{

    /**
     * Page not found
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\ViewException
     * @throws \ReflectionException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function apply(): void
    {
        $response = Di::get(Response::class);
        $view = (new ViewFactory())->renderPartial('errors/404');
        $response->html($view);
    }

}
