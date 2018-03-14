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
 * ModuleLoader Class
 * 
 * ModuleLoader class allows loads modules
 * 
 * @package Quantum
 * @subpackage Routes
 * @category Routes
 */
class ModuleLoader {
    /**
     * List of loaded modules
     * 
     * @var array 
     */
    public $modules = array();
    
    /**
     *  List of routes
     * 
     * @var array
     */
    public $routes = array();

    /**
     * Class constructor
     * 
     * Runs the module loader
     * 
     * @return void
     */
    public function __construct() {
       
        try {
            $this->loadModules();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    /**
     * Load Modules
     * 
     * @return void
     * @throws \Exception When module file is not found
     */
    private function loadModules() {
        $this->modules = require_once BASE_DIR . '/config/modules.php';
        
        foreach ($this->modules['modules'] as $module) {
            if(!file_exists(MODULES_DIR . '/' . $module . '/Config/routes.php')) {
                throw new \Exception(_message(ExceptionMessages::MODULE_NOT_FOUND, $module));
            } 
            
            $routes = require_once MODULES_DIR . '/' . $module . '/Config/routes.php';
            
            foreach($routes as $route) {
                $this->routes[] = array(
                    'uri' => $route[0],
                    'method' => $route[1],
                    'controller' => $route[2],
                    'action' => $route[3],
                    'module' => $module,
                );
            }
        }
    }

}
