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
 * @since 1.0.0
 */


namespace Quantum\Hooks;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Exceptions\RouteException;
use Quantum\Http\Response;

/**
 * HookDefaults Class
 * 
 * Default implementations
 * 
 * @package Quantum
 * @subpackage Hooks
 * @category Hooks
 */
class HookDefaults implements HookInterface {

    /**
     * handleHeaders
     * 
     * Allows Cross domain requests
     * 
     * @return void
     */
    public static function handleHeaders() {

        Response::setHeader('Access-Control-Allow-Origin', '*');
        Response::setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        Response::setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    /**
     * Page not found
     * 
     * @return void
     * @throws RouteException When route not found
     */
    public static function pageNotFound() {
        throw new RouteException(ExceptionMessages::ROUTE_NOT_FOUND);
    }

}
