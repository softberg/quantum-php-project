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

/**
 * Database Abstract Layer interface
 * 
 * The common interface for DBAL, which should implemented by all DBAL classes
 * 
 * @package Quantum
 * @subpackage Libraries.Database
 * @category Libraries
 */
interface DbalInterface {

    /**
     * DB Connect
     * 
     * Should be implemented in classes for db connect
     * 
     * @param mixed $connectionString
     */
    public static function dbConnect($connectionString);
    
    /**
     * Find One
     * 
     * Should be implemented in classes to get record by primary key
     * 
     * @param mixed $params
     */
    public static function findOne($params);
    
    /**
     * FindOneBy
     * 
     * Should be implemented in classes to get record by given column
     * 
     * @param mixed $params
     */
    public static function findOneBy($params);
    
    /**
     * Create
     * 
     * Should be implemented in classes for creating new db record
	 *
	 * @param array $params
     */
    public static function create($params);
    
}
