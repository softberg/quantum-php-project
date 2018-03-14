<?php

namespace Modules\Api\Controllers;

use Quantum\Hooks\HookInterface;
use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Modules\Main\Models\User;
use Firebase\JWT\JWT;

class ApiBaseController extends Qt_Controller implements HookInterface {

    protected $token;

    public function __construct() {

//        $secretKey = get_config('jwt_secret_key');
//
//        $data = [
//            'iat' => time(), // Issued at: time when the token was generated
//            'jti' => uniqid(), // Json Token Id: an unique identifier for the token
//            'iss' => base_url(), // Issuer
//            'nbf' => time() + 10, // Not before
//        ];
//
//        JWT::$leeway = 60;
//        $jwt = JWT::encode(
//            $data, 
//            $secretKey,
//            'HS512'
//        );
//        
//        $token = JWT::decode($jwt, $secretKey, array('HS512'));
//
//        
//        echo "<pre>"; print_r($token);
//        
//        $this->token = Request::getCSRFToken();
//
//        $user = $this->modelFactory('User');
    }

    public static function methodOptionHeaders() {
        Response::setHeader('Access-Control-Allow-Origin', 'http://quantum.dev');
        Response::setHeader('Access-Control-Allow-Headers', 'Origin, X-CSRF-Token, X-Requested-With, Content-Type, Accept');
        Response::setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

}
