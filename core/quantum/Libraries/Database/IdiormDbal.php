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

use Quantum\Mvc\Qt_Model;
use ORM;

/**
 * IdiORM DBAL
 * 
 * Database Abstract Layer class for IdiOrm
 * Default DBAL for framework
 * 
 * @package Quantum
 * @subpackage Libraries.Database
 * @category Libraries
 */

class IdiormDbal implements DbalInterface {

    /**
     * DB Connect
     * 
     * Connects to database
     * 
     * @param array $connectionString
     * @uses ORM::configure Idiorm
     * @return void
     */
    public static function dbConnect($connectionString) {
        ORM::configure(array(
            'connection_string' => $connectionString['driver'] . ':host=' . $connectionString['host'] . ';dbname=' . $connectionString['dbname'],
            'username' => $connectionString['username'],
            'password' => $connectionString['password']
        ));
    }
    
    /**
     * Find one
     * 
     * Gets record by primary key
     * 
     * @param array $params
     * @uses ORM Idiorm
     * @return object
     */
    public static function findOne($params) {
        return ORM::for_table($params['table'])->find_one($params['args'][0]);
    }
    
    /**
     * FindOneBy
     * 
     * Gets record by given column
     * 
     * @param array $params
     * @uses ORM Idiorm
     * @return object
     */
    public static function findOneBy($params) {
        return ORM::for_table($params['table'])->where($params['args'][0], $params['args'][1])->find_one();
    }
    
    /**
     * Create
     * 
     * Creates new db record
     * 
	 * @param array $params
     * @uses ORM Idiorm
     * @return object
     */
    public static function create(params) {
        return ORM::for_table($params['table'])->create();
    }
    
}
