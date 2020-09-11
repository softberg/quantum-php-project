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
/**
 * Directory separator
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Base directory of project.
 */
if (!defined('BASE_DIR')) {
    define('BASE_DIR', __DIR__ . DS . '..');
}

/*
 * ---------------------------------------------------------
 * Including project constant definitions
 * ---------------------------------------------------------
 */
require_once dirname(__DIR__) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'constants.php';


/*
 * ---------------------------------------------------------
 * Register the auto loader
 * ---------------------------------------------------------
 */
require_once VENDOR_DIR . '/autoload.php';


/*
 * ---------------------------------------------------------
 * Includes the core bootstrap
 * ---------------------------------------------------------
 */
require_once CORE_DIR . '/Bootstrap.php';
