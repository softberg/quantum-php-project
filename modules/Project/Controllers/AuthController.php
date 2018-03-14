<?php

namespace Modules\Project\Controllers;

use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Modules\Project\Models\User;

class AuthController extends Qt_Controller {

    public function __construct() {
        $this->setLayout('layouts/auth');
    }

    public function indexAction() {

        //echo "<pre>"; print_r($_REQUEST);die;
//        header('HTTP/1.1 403 Forbidden');
//        header( 'HTTP/1.1 400: BAD REQUESTik' );
        
        //echo Request::getCSRFToken();

        //print_r(Request::all());
        die;

        $student_info[] = array(
            'id' => '001',
            'name' => 'Mifas',
            'subjects' => array('English', 'Maths', 'IT')
        );
        $student_info[] = array(
            'id' => '002',
            'name' => 'Ijas',
            'subjects' => array('Science', 'History', 'Social')
        );

        //echo Response::xml($student_info);

        $this->render('index', [
            'a' => 'foo',
            'b' => 'bar'
        ]);
    }

    public function signinAction() {
        //$email = Request::post('email');
        echo $pass = Request::post('pass', '123456qwerty');
        
        //Request::get('name');

        // echo $pass;
        //echo $a = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        //echo "siginin";
    }

    public function signUpAction() {
        $user = $this->modelFactory('User');

        $user->create();
        $user->email = Request::post('email');
        $user->pass = Request::post('pass');
        $user->firstname = Request::post('firstname');
        $user->lastname = Request::post('lastname');

        $user->save();
    }

}
