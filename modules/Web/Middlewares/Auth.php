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

namespace Modules\Web\Middlewares;

use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Auth
 * @package Modules\Web\Middlewares
 */
class Auth extends Qt_Middleware
{

    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!auth()->check()) {
            redirect(base_url() . '/' . current_lang() . '/signin');
        }

        return $next($request, $response);
    }

}
