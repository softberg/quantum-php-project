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

/**
 * Base View Class
 * 
 * Qt_View class is a base class that responsible for rendering and 
 * outputting the view
 * 
 * @package Quantum
 * @subpackage MVC
 * @category MVC
 */
class Qt_View {

    /**
     * Current route
     * 
     * @var mixed 
     */
    private static $currentRoute;
    
    /**
     * Layout file
     * 
     * @var string 
     */
    private static $layout;
    
    /**
     * View file
     * 
     * @var string 
     */
    public static $view;

    /**
     * Class constructor 
     * 
     * @param mixed $currentRoute
     * @return void
     */
    public function __construct($currentRoute) {
        self::$currentRoute = $currentRoute;
    }

    /**
     * Sets a layout
     * 
     * @param string $layout
     * @return void
     */
    public function setLayout($layout) {
        self::$layout = $layout;
    }

    /**
     * Renders a layout
     * 
     * @param array $sharedData
     * @return string
     */
    public function renderLayout($sharedData = array()) {
        return self::renderFile(self::findFile(self::$layout), '', $sharedData);
    }

    /**
     * Render
     * 
     * Renders a view
     * 
     * @param string $view
     * @param array $params
     * @param bool $output
     * @param array $sharedData
     * @uses self::renderFile
     * @return void
     */
    public function render($view, $params = array(), $output = false, $sharedData = array()) {
        self::$view = self::renderFile(self::findFile($view), $params, $sharedData);

        if ($output) {
            echo self::$view;
        }

        if (!empty(self::$layout)) {
            echo self::renderLayout($sharedData);
        }
    }
    
    /**
     * Output
     * 
     * Outputs the view
     * 
     * @param string $view
     * @param string $modulePath
     * @param array $params
     * @param array $sharedData
     * @uses self::renderFile
     * @return void
     */
    public function output($view, $modulePath, $params = array(), $sharedData = array()) {
        self::$view = self::renderFile(self::findFile($view, $modulePath), $params, $sharedData);
        echo self::$view;
    }

    /**
     * Find File
     * 
     * Finds a given file
     * 
     * @param string $file
     * @param string $modulePath
     * @return string
     * @throws \Exception When file is not found
     */
    private function findFile($file, $modulePath = NULL) {
        $filePath = MODULES_DIR . '/' . ($modulePath ? $modulePath : self::$currentRoute['module']) . '/Views/' . $file . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception(_message(ExceptionMessages::VIEW_FILE_NOT_FOUND, $file));
        }

        return $filePath;
    }

    /**
     * Render File
     * 
     * Renders a view  
     * 
     * @param string $file
     * @param array $parmas
     * @param array $sharedData
     * @return string
     */
    private function renderFile($file, $parmas = array(), $sharedData = array()) {
        ob_start();
        ob_implicit_flush(false);

        if ($parmas) {
            extract($parmas, EXTR_OVERWRITE);
        }
        
        if ($sharedData) {
            extract($sharedData, EXTR_OVERWRITE);
        }

        require $file;

        return ob_get_clean();
    }

}
