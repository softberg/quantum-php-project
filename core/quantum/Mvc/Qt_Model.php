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
use Quantum\Libraries\Database\Database;
use Quantum\Hooks\HookManager;
use ORM;

/**
 * Base Model Class
 * 
 * Qt_Model class is a base abstract class that every model should extend,
 * This class also connects to database and prepares object relational mapping
 * 
 * @package Quantum
 * @subpackage MVC
 * @category MVC
 */
abstract class Qt_Model {

    /**
     * ORM
     * @var object 
     */
    private $orm;
    
    /**
     * Path to ORM class
     * 
     * @var string 
     */
    private $ormPath;
    
    /**
     * Current route
     * 
     * @var mixed 
     */
    private $currentRoute;
    
    /**
     * The database table associated with model
     * 
     * @var string 
     */
    protected $table;
    
    /**
     * Models fillable properties 
     * @var array 
     */
    protected $fillable = array();

    /**
     * Class constructor 
     * 
     * @param mixed $currentRoute
     * @return void
     * @throws \Exception When called directly
     */
    public final function __construct($currentRoute = NULL) {
        if (!isset($currentRoute)) {
            throw new \Exception(ExceptionMessages::DIRECT_MODEL_CALL);
        }

        $this->currentRoute = $currentRoute;
		
		if (!Database::connected())
			(new Database($currentRoute))->connect();
        
        $this->ormPath = Database::getORM();
    }

    /**
     * Fill Object Properties
     * 
     * Fills the properties with values
     * 
     * @param array $arguments
     * @return void
     * @throws \Exception When the property is not appropriate
     */
    public function fillObjectProps($arguments) {
        foreach ($arguments as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                throw new \Exception(_message(ExceptionMessages::INAPPROPRIATE_PROPERTY, $key));
            }

            $this->$key = $value;
        }
    }

    /**
     * Update rules
     * 
     * Updates the validation rules of model
     * 
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function updateRules($key, $value) {
        $this->rules[$key] = $value;
    }

    /**
     * __get magic
     * 
     * Allows to access to models property
     * 
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        return isset($this->orm->$property) ? $this->orm->$property : NULL;
    }

    /**
     * __set magic
     * 
     * Allows to set values to models properties
     * 
     * @param string $property
     * @param mixed $vallue
     */
    public function __set($property, $value) {
        $this->orm->$property = $value;
    }

    /**
     * __call magic
     * 
     * Allows to call models methods
     * 
     * @param string $method
     * @param mixed $args
     * @return void
     */
    public function __call($method, $args = NULL) {
        switch ($method) {
            case 'findOne':
//                $this->orm = ORM::for_table($this->table)->$method($args);
                $this->orm = HookManager::call($method, array('table' => $this->table, 'args' => $args), $this->ormPath);
                break;
            case 'findOneBy':
//                $this->orm = ORM::for_table($this->table)->where($args[0], $args[1])->find_one();
                $this->orm = HookManager::call($method, array('table' => $this->table, 'args' => $args), $this->ormPath);
                break;
            case 'asArray':
                return $this->orm ? $this->orm->as_array() : array();
                break;
            case 'create':
//                $this->orm = ORM::for_table($this->table)->create();
                $this->orm = HookManager::call($method, array('table' => $this->table), $this->ormPath);
                break;
            case 'save':
                $this->orm->save();
                break;
            case 'delete':
                $this->orm->delete();
                break;
        }
    }

}
