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

namespace Quantum\Mvc;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Exceptions\RouteException;

/**
 * MvcManager Class
 * 
 * MvcManager class determine the controller, action of current module based on
 * current route
 * 
 * @package Quantum
 * @subpackage MVC
 * @category MVC
 */
class MvcManager {

    /**
     * Run MVC
     * 
     * Runs the action of controller of current module
     * 
     * @param array $currentRoute
     * @throws RouteException When controller, action not found
     */
    public function runMvc($currentRoute) {
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            $controllerPath = MODULES_DIR . '/' . $currentRoute['module'] . '/Controllers/' . $currentRoute['controller'] . '.php';

            if (!file_exists($controllerPath)) {
                throw new RouteException(_message(ExceptionMessages::CONTROLLER_NOT_FOUND, $currentRoute['controller']));
            }

            require_once $controllerPath;

            $controllerClass = '\\Modules\\' . $currentRoute['module'] . '\\Controllers\\' . $currentRoute['controller'];

            if (!class_exists($controllerClass, FALSE)) {
                throw new RouteException(_message(ExceptionMessages::CONTROLLER_NOT_DEFINED, $currentRoute['controller']));
            }

            $controller = new $controllerClass();

            $action = $currentRoute['action'];

            if (!method_exists($controller, $action)) {
                throw new RouteException(_message(ExceptionMessages::ACTION_NOT_DEFINED, $action));
            }

            if (method_exists($controller, '__before')) {
                call_user_func_array(array($controller, '__before'), $currentRoute['args']);
            }

            call_user_func_array(array($controller, $action), $currentRoute['args']);

            if (method_exists($controller, '__after')) {
                call_user_func_array(array($controller, '__after'), $currentRoute['args']);
            }
        }
    }

}
