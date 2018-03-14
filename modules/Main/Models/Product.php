<?php

namespace Modules\Main\Models;
use Quantum\Mvc\Qt_Model;


class Product extends Qt_Model {
    public $name;
    
    public function getProductName() {
        echo "<pre>"; print_r(self::$dbConnections);
    }
}
