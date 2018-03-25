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
     * Criterias
     * 
     * Adds where criterias
     * 
     * @param array $params
     * @uses ORM Idiorm
     * @return object
     */
    public static function criterias($params) {
        $orm = ORM::for_table($params['table']);

        foreach ($params['args'][0] as $column => $value) {
            $orm->where($column, $value);
        }

        return $orm;
    }

    /**
     * First
     * 
     * Gets the first item
     * 
     * @param ORM $ormObject
     * @uses ORM Idiorm
     * @return object
     */
    public function first($ormObject) {
        return $ormObject->find_one();
    }
    
    /**
     * Get
     * 
     * Gets the result set
     * 
     * @param array $params
     * @uses ORM Idiorm
     * @return mixed
     */
    public function get($params) {
        return ($params['args'] && $params['args'][0] == 'object') ? $params['orm']->find_many() : $params['orm']->find_array();
    }

    /**
     * asArray
     * 
     * Casts the orm object to array
     * 
     * @param ORM $ormObject
     * @uses ORM Idiorm
     * @return array
     */
    public static function asArray($ormObject) {
        return $ormObject ? $ormObject->as_array() : array();
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
    public static function create($params) {
        return ORM::for_table($params['table'])->create();
    }
    
    /**
     * Save
     * 
     * Saves the data into the database
     * 
     * @param ORM $ormObject
     * @uses ORM Idiorm
     */
    public static function save($ormObject) {
        $ormObject->save();
    }
    
    /**
     * Delete
     * 
     * Deletes the data from the database
     * 
     * @param ORM $ormObject
     * @uses ORM Idiorm
     */
    public static function delete($ormObject) {
        $ormObject->delete();
    }

}
