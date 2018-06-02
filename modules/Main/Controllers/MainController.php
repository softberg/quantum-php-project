<?php

namespace Modules\Main\Controllers;

use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Modules\Main\Models\User;

class MainController extends Qt_Controller {

    public function __construct() {
        
        $this->setLayout('layouts/main');
    }

    public function __before() {
        // echo "Before Action";
    }

    public function __after() {
        //echo "After Action";
    }

    public function indexAction() {
        $user = $this->modelFactory('User');

        $user->criterias(['email' => 'a.delon@mail.com'])->first();
        $user->pass = 44444;
        $user->save();
        
        $this->render('index');
    }

}
