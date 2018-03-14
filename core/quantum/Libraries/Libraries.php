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

namespace Quantum\Libraries;

use Quantum\Routes\RouteController;

/**
 * Loader Class
 * 
 * Libraries class is a loader class of libraries
 * 
 * @package Quantum
 * @subpackage Libraries
 * @category Libraries
 */
class Libraries {

    /**
     * Loads 3rd party libraries
     * 
     * @return void
     */
    public static function load() {
        $helpersDir = BASE_DIR . 'libraries';
        foreach (glob($helpersDir . "/*.php") as $filename) {
            require_once $filename;
        }
    }

}
