<?php

namespace Modules\Main\Libraries;

use Quantum\Libraries\Database\DbalInterface;
use Quantum\Mvc\Qt_Model;
use ORM;

class OtherDbal implements DbalInterface {

    public static function dbConnect($connectionString) {
        ORM::configure(array(
            'connection_string' => $connectionString['driver'] . ':host=' . $connectionString['host'] . ';dbname=' . $connectionString['dbname'],
            'username' => $connectionString['username'],
            'password' => $connectionString['password']
        ));
    }
    
    public static function findOne($params) {
        return 111;
    }
    
    public static function findOneBy($params) {
        return 222;
    }

    public static function create() {
        return 333;
    }
    
}
