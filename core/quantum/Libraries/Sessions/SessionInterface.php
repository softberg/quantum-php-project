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

namespace Quantum\Libraries\Sessions;

/**
 * Sessions interface
 * 
 * The common interface, which should implemented by session classes
 * 
 * @package Quantum
 * @subpackage Libraries.Sessions
 * @category Libraries
 */
interface SessionInterface {
    
    /**
     * Should be implemented in classes to get value from session by key
     * 
     * @param string $key
     */
    public function get($key);
    
    /**
     * Should be implemented in classes to get whole session data
     */
    public function all();
    
    /**
     * Should be implemented in classes to check if session contains a key
     * 
     * @param string $key
     */
    public function has($key);

    /**
     * Should be implemented in classes to set session value with given key
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);
    
    /**
     * Should be implemented in classes to get flesh values by given key
     * 
     * @param string $key
     */
    public function getFlesh($key);
    
    /**
     * Should be implemented in classes to set flesh values with given key
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setFlesh($key, $value);

    /**
     * Should be implemented in classes to delete data with given key from session
     * 
     * @param string $key
     */
    public function delete($key);
    
    /**
     * Should be implemented in classes to delete whole session data
     */
    public function flush();

    
}
