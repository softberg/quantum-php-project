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

namespace Quantum\Routes;

use Quantum\Exceptions\RouteException;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Hooks\HookManager;
use Quantum\Hooks\HookController;

/**
 * Router Class
 * 
 * Router class parses URIS and determine routing
 * 
 * @package Quantum
 * @subpackage Routes
 * @category Routes
 */
class Router extends RouteController {

    /**
     * List of routes
     *
     * @var array 
     */
    private $routes;

    /**
     * Class constructor
     * 
     * Runs the route mapping.
     * 
     * @param \Quantum\Routes\ModuleLoader $moduleLoader
     * @return void
     */
    public function __construct(ModuleLoader $moduleLoader) {
        $this->routes = $moduleLoader->routes;
    }

    /**
     * Find Route
     * 
     * Matches any routes that may exists in config/routes.php file of specific module 
     * against the URI to determine current route and current module
     * 
     * @return void
     * @throws RouteException When repetitve route was found
     */
    public function findRoute() {
        if (isset($_SERVER['REQUEST_URI'])) {

            $matched_uris = array();
            $routes_group = array();

            foreach ($this->routes as $route) {

                $route['uri'] = str_replace('/', '\/', $route['uri']);
                $route['uri'] = preg_replace('/\[:num\]/', '([0-9]+)', $route['uri']);
                $route['uri'] = preg_replace('/\[:alpha\]/', '([a-zA-Z]+)', $route['uri']);
                $route['uri'] = preg_replace('/\[:any\]/', '([^\/].+)', $route['uri']);
                
                $request_uri = preg_replace('/[?]/', '', $_SERVER['REQUEST_URI']);

                preg_match("/^\/" . $route['uri'] . "$/", $request_uri, $matches);

                if ($matches) {
                    array_push($matched_uris, $matches[0]);
                    array_shift($matches);
                    $route['args'] = $matches;
                    array_push($routes_group, $route);
                }
            }

            if (!$matched_uris) {
                self::$currentRoute = NULL;
                HookManager::call('pageNotFound');
            }

            if ($matched_uris) {
                if (count($routes_group) > 1) {

                    self::$currentModule = $routes_group[0]['module'];

                    for ($i = 0; $i < count($routes_group) - 1; $i++) {
                        for ($j = $i + 1; $j < count($routes_group); $j++) {
                            if ($routes_group[$i]['method'] == $routes_group[$j]['method']) {
                                self::$currentRoute = NULL;
                                throw new RouteException(_message(ExceptionMessages::REPETITIVE_ROUTE_SAME_METHOD, $routes_group[$j]['method']));
                                break 2;
                            }
                            if ($routes_group[$i]['module'] != $routes_group[$j]['module']) {
                                self::$currentRoute = NULL;
                                throw new RouteException(ExceptionMessages::REPETITIVE_ROUTE_DIFFERENT_MODULES);
                                break 2;
                            }
                        }
                    }
                    foreach ($routes_group as $route) {
                        if ($_SERVER['REQUEST_METHOD'] == $route['method']) {
                            self::$currentRoute = $route;
                            break;
                        }
                    }
                } else {
                    self::$currentModule = $routes_group[0]['module'];
                    self::$currentRoute = $routes_group[0];
                }
            }

            HookManager::call('handleHeaders');

            if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
                $this->checkMethod();
            }
        }
    }

    /**
     * Matches the http method defined in config/routes.php file of specific module 
     * against request method determine current route
     * 
     * @return void
     * @throws RouteException When Http method is other the defined in config/routes.php of sepcific mosule
     */
    private function checkMethod() {
        if ($_SERVER['REQUEST_METHOD'] != self::$currentRoute['method']) {
            throw new RouteException(_message(ExceptionMessages::INCORRECT_METHOD, $_SERVER['REQUEST_METHOD']));
        }
    }

}
