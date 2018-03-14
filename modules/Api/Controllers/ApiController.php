<?php

namespace Modules\Api\Controllers;

use Quantum\Http\Request;
use Quantum\Libraries\Validation\Validation;
use Modules\Api\Models\User;

class ApiController extends ApiBaseController {

    public function login() {
//        $this->curl->post($this->apiUrl . '?action=user_signIn', array(
//            'username' => $this->username,
//            'password' => $this->password,
//        ));
//
//        if ($this->curl->error) {
//            echo $this->curl->error_code;
//        } else {
//            print_r($this->curl->response);
//        }
    }

    public function index() {
        session()->set('test', 'foo');
        session()->set('bar', 'baz');
    }

    public function test() {
//        $user = $this->modelFactory('User');
//        echo "<pre>"; print_r($user);
        print_r(session()->get('bar'));
        
        
        
    }

    public function create() {
        $user = $this->modelFactory('User');
        $validated = Validation::is_valid(Request::all(), $user->rules);

        if ($validated === true) {
            echo "Ok";
        } else {
            print_r($validated);
        }
    }

    public function update() {
        print_r(Request::all());
    }

    public function delete() {
        Request::all();
    }

}
