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

namespace Quantum\Http;

/**
 * HttpRequest Class
 * 
 * Abstract base http request class
 * 
 * @package Quantum
 * @subpackage Http
 * @category Http
 */

abstract class HttpRequest {

    /**
     * Get headers
     * 
     * @return array
     */
    protected static function getHeaders() {
        return getallheaders();
    }

    /**
     * Get Param
     * 
     * Gets the param from request by given key
     * 
     * @param string $method
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected static function getParam($method, $key, $default = NULL) {
        if ($method == 0) {  // POST Method
            if (isset($_REQUEST[$key]) && is_array($_REQUEST[$key])) {
                $param = filter_input($method, $key, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            } else {
                $param = filter_input($method, $key, FILTER_SANITIZE_STRING, FILTER_REQUIRE_SCALAR);
            }
        } else if ($method == 1) {  // GET Method
            $param = filter_input($method, $key, FILTER_SANITIZE_STRING);
        }

        return $param ? $param : $default;
    }

    /**
     * Get all params
     * 
     * @return array
     */
    protected static function getAllParams() {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE') {

            $input = file_get_contents('php://input');

            $encoded_data = array();

            if (isset($_SERVER['CONTENT_TYPE'])) {
                switch ($_SERVER['CONTENT_TYPE']) {
                    case 'application/x-www-form-urlencoded':
                        parse_str($input, $encoded_data);
                        break;
                    case 'application/json':
                        $encoded_data = json_decode($input);
                        break;
                    default :
                        $encoded_data = parse_raw_http_request($input);
                        break;
                }
            }

            if ($encoded_data) {
                foreach ($encoded_data as $key => $val) {
                    $_REQUEST[$key] = $val;
                }
            }
        }

        return $_REQUEST;
    }

}
