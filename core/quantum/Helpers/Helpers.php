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


namespace Quantum\Helpers;

use Quantum\Routes\RouteController;

/**
 * Loader Class
 * 
 * Helper class is a loader class of helpers
 * 
 * @package Quantum
 * @subpackage Helpers
 * @category Helpers
 */
class Helpers {

    /**
     * Loads 3rd party helpers
     * 
     * @return void
     */
    public static function load() {
        $helpersDir = BASE_DIR . 'helpers';
        foreach (glob($helpersDir . "/*.php") as $filename) {
            require_once $filename;
        }
    }

}
