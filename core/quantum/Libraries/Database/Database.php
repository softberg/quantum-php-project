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

namespace Quantum\Libraries\Database;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Routes\RouteController;
use Quantum\Hooks\HookManager;
use ORM;

/**
 * Database class
 * 
 * Initialize the database
 * 
 * @package Quantum
 * @subpackage Libraries.Database
 * @category Libraries
 */
class Database {

    /**
     * Current route
     * 
     * @var mixed 
     */
    private $currentRoute;
    
    /**
     * Path to ORM class
     * 
     * @var string 
     */
    private static $ormPath;
    
    /**
     * Connection parameters
     * 
     * @var mixed 
     */
    private static $dbConnections = NULL;

    /**
     * Class constructor 
     * 
     * @param mixed $currentRoute
     * @return $this Database instance
     */
    public function __construct($currentRoute) {
        $this->currentRoute = $currentRoute;
        return $this;
    }
    
    /**
     * Connected
     * 
     * Checks the connection with database
     * 
     * @uses HookManager::call
     * @return bool
     */
    public function connected() {
        if(self::$dbConnections == NULL)
            return false;
        
        return true;
    }

    /**
     * Connect
     * 
     * Connects to database
     * 
     * @uses HookManager::call
     * @return void
     */
    public function connect() {
        $dbConfig = $this->getDbConfig();
        $this->setDbConfig($dbConfig);
        $this->setORM($dbConfig);
        HookManager::call('dbConnect', self::$dbConnections, self::$ormPath);
    }
    
    /**
     * Get ORM
     * 
     * Gets the ORM defined in config/database.php if exists, otherwise 
     * default ORM
     * 
     * @return string
     */
    public static function getORM() {
        return self::$ormPath;
    }

    /**
     * Get DB Config
     * 
     * Gets db configs from current config/database.php of module or 
     * from top config/database.php if in module it's not defined
     * 
     * @return array
     * @throws \Exception When config is not found or incorrect
     */
    private function getDbConfig() {
        if (file_exists(MODULES_DIR . DS . $this->currentRoute['module'] . '/Config/database.php')) {
            $dbConfig = require_once MODULES_DIR . DS . $this->currentRoute['module'] . '/Config/database.php';

            if (!empty($dbConfig) && is_array($dbConfig)) {
                return $dbConfig;
            } else {
                throw new \Exception(ExceptionMessages::INCORRECT_CONFIG);
            }
        } else {
            if (file_exists(BASE_DIR . '/config/database.php')) {
                $dbConfig = require_once BASE_DIR . '/config/database.php';
                if (!empty($dbConfig) && is_array($dbConfig)) {
                    return $dbConfig;
                } else {
                    throw new \Exception(ExceptionMessages::INCORRECT_CONFIG);
                }
            } else {
                throw new \Exception(ExceptionMessages::DB_CONFIG_NOT_FOUND);
            }
        }
    }

    /**
     * Sets the db configs
     * 
     * @param array $dbConfig
     */
    private function setDbConfig(array $dbConfig) {
        self::$dbConnections = $dbConfig[$dbConfig['current']];
    }

    /**
     * Sets ORM
     * 
     * @param array $dbConfig
     */
    private function setORM(array $dbConfig) {
        self::$ormPath = (isset($dbConfig['orm']) && !empty($dbConfig['orm']) ? $dbConfig['orm'] : '\\Quantum\\Libraries\\Database\\IdiormDbal');
    }

}
