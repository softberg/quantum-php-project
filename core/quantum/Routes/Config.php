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

use Quantum\Exceptions\ExceptionMessages;

/**
 * Config Class
 * 
 * Config class allows to load or import configuration files
 * 
 * @package Quantum
 * @subpackage Routes
 * @category Routes
 */
class Config {

    /**
     *
     * @var array 
     */
    public static $configs = array();

    /**
     * Finds and loads configuration 
     * 
     * @return void
     * @throws \Exception When config file is not found
     */
    public static function load() {
        $configFile = MODULES_DIR . DS . RouteController::$currentModule . '/Config/config.php';

        if (!file_exists($configFile)) {
            $configFile = BASE_DIR . '/config/config.php';
            if (!file_exists($configFile)) {
                throw new \Exception(ExceptionMessages::CONFIG_FILE_NOT_FOUND);
            }
        }

        if (empty(self::$configs)) {
            self::$configs = require_once $configFile;
        }
    }

    /**
     * Imports libraries, helpers and other classes  
     * 
     * @return void
     * @throws \Exception When config file is not found or there are config collision between modules
     */
    public static function import($filename) {
        $configFile = BASE_DIR . '/config/' . $filename . '.php';

        if (!file_exists($configFile)) {
            throw new \Exception(ExceptionMessages::CONFIG_FILE_NOT_FOUND);
        }
        
        $allConfigs = self::getAll();
        foreach ($allConfigs as $key => $config) {
            if($filename == $key) { 
                throw new \Exception(ExceptionMessages::CONFIG_COLLISION);
            }
        }

        self::$configs[$filename] = require_once BASE_DIR . '/config/' . $filename . '.php';
    }
    /**
     * Returns all config data
     * 
     * @return array
     */
    public static function getAll() {
        return self::$configs;
    }

    /**
     * Gets a config item
     * 
     * @param type $key config index name
     * @return mixed|null The configuration tem or NULL, if the item does not exists
     */
    public static function get($key) {
        if (isset(self::$configs[$key]))
            return self::$configs[$key];

        return NULL;
    }

}
