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

use Quantum\Exceptions\ExceptionMessages;

/**
 * Request Class
 * 
 * Receive requests 
 * 
 * @package Quantum
 * @subpackage Http
 * @category Http
 */
class Request extends HttpRequest {

    /**
     * Get
     * 
     * Responsible for get type requests
     * 
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public static function get($key, $default = NULL) {
        return parent::getParam(INPUT_GET, $key, $default);
    }

    /**
     * Post
     * 
     * Responsible for post type requests
     * 
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public static function post($key, $default = NULL) {
        return parent::getParam(INPUT_POST, $key, $default);
    }

    /**
     * Gets all params
     * 
     * @return array
     */
    public static function all() {
        return parent::getAllParams();
    }

    /**
     * Gets Сross Site Request Forgery Token
     * 
     * @return string
     * @throws \Exception When Token not found
     */
    public static function getCSRFToken() {
        $allHeaders = array_change_key_case(parent::getHeaders(), CASE_UPPER);

        if (array_key_exists('X-CSRF-TOKEN', $allHeaders)) {
            return $allHeaders['X-CSRF-TOKEN'];
        } else {
            throw new \Exception(ExceptionMessages::CSRF_TOKEN_NOT_FOUND);
        }
    }

    /**
     * isAjax
     * 
     * Checks to see if request was ajax request
     * 
     * @return boolean
     */
    public static function isAjax() {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }

        return false;
    }

}
