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

namespace Quantum\Hooks;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Exceptions\RouteException;

/**
 * HookManager Class
 * 
 * Provides a mechanism to extend the core.
 * 
 * @package Quantum
 * @subpackage Hooks
 * @category Hooks
 */
class HookManager {

    /**
     * Call method
     * 
     * @param string $hookName
     * @param array $args
     * @param string $alternativePath
     * @return object
     * @throws \Exception When Hook not found
     */
    public static function call($hookName, $args = array(), $alternativePath = NULL) {
        $hookImplementer = self::hasImplementer($hookName);

        if (!empty($hookImplementer)) {
            $implementerClass = '\\Hooks\\' . $hookImplementer;
            $implementer = new $implementerClass();
            $implementer->$hookName($args);
        } else {
            $defaultImplementer = self::hasDefaultImplementer($hookName, $alternativePath);
           
            if ($defaultImplementer) {
                return $defaultImplementer::$hookName($args);
            } else {
                throw new \Exception(_message(ExceptionMessages::UNDECLARED_HOOK_NAME, $hookName));
            }
        }
    }

    /**
     * hasImplementer
     * 
     * @param string $hookName
     * @return string Implementer class name
     * @throws \Exception When duplicate hook name detected
     */
    private static function hasImplementer($hookName) {
        $classNames = get_directory_classes(BASE_DIR . DS . 'hooks');

        $duplicates = 0;

        $hookImplementer = NULL;

        foreach ($classNames as $className) {
            $implementerClass = '\\Hooks\\' . $className;
            if (class_exists($implementerClass, TRUE)) {
                $class = new \ReflectionClass('\\Hooks\\' . $className);
                if ($class->implementsInterface('Quantum\\Hooks\\HookInterface')) {
                    if ($class->hasMethod($hookName)) {
                        $hookImplementer = $className;
                        $duplicates++;
                    }
                }
            }
        }

        if ($duplicates > 1) {
            throw new \Exception(ExceptionMessages::DUPLICATE_HOOK_IMPLEMENTER);
        }

        return $hookImplementer;
    }

    /**
     * hasDefaultImplementer 
     * 
     * @param string $hookName
     * @param string $alternativePath
     * @return boolean
     */
    private static function hasDefaultImplementer($hookName, $alternativePath = NULL) {
        $classPath = $alternativePath ? $alternativePath : '\\Quantum\\Hooks\\HookDefaults';
        $class = new \ReflectionClass($classPath);

        if ($class->hasMethod($hookName)) {
            return $class->getName();
        }

        return FALSE;
    }

}
