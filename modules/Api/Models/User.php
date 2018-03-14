<?php

namespace Modules\Api\Models;

use Quantum\Mvc\Qt_Model;

class User extends Qt_Model {

    public $table = 'user';
    
    public $fillable = [
        'firstname',
        'lastname',
        'email',
        'pass',
    ];
    
    public $rules = [
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|unique,user',
        'pass' => 'required|min_len,6',
    ];

}
