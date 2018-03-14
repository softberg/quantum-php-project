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

/**
 * RouterController Class
 * 
 * Base abstract class
 * 
 * @package Quantum
 * @subpackage Routes
 * @category Routes
 */

abstract class RouteController {

    /**
     * Contains current route information
     * 
     * @var mixed 
     */
    public static $currentRoute = NULL;
    
    /**
     * Contains current route information
     * 
     * @var mixed 
     */
    public static $currentModule = NULL;

}
