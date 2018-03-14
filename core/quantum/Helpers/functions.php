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

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Routes\Config;
use Quantum\Routes\RouteController;
use Quantum\Libraries\Sessions\SessionManager;
use Quantum\Http\Response;
use Quantum\Mvc\Qt_Controller;
use Quantum\Mvc\Qt_View;

if (!function_exists('qt_instance')) {

    /**
     * Qt Controller instance
     * 
     * @return object
     */
    function qt_instance() {
        return Qt_Controller::getInstance();
    }

}

if (!function_exists('view')) {

    /**
     * Rendered view
     * 
     * @return string
     */
    function view() {
        return Qt_View::$view;
    }

}

if (!function_exists('get_current_module')) {

    /**
     * Gets current module
     * 
     * @return string
     */
    function get_current_module() {
        return RouteController::$currentModule;
    }

}

if (!function_exists('current_controller')) {

    /**
     * Get current controller
     * 
     * @return string
     */
    function current_controller() {
        return RouteController::$currentRoute['controller'];
    }

}

if (!function_exists('current_action')) {

    /**
     * Gets current action
     * 
     * @return string
     */
    function current_action() {
        return RouteController::$currentRoute['action'];
    }

}

if (!function_exists('session')) {

    /**
     * Gets session handler
     * 
     * @return object
     */
    function session() {
        return SessionManager::getSessionHandler();
    }

}

if (!function_exists('redirect')) {

    /**
     * Redirect
     * 
     * @param string $url
     * @param integer $code
     */
    function redirect($url, $code = NULL) {
        if ($code)
            Response::setStatus($code);

        Response::setHeader('Location', $url);
        exit;
    }

}

if (!function_exists('get_referrer')) {

    /**
     * Gets the referrer
     * 
     * @return string|null
     */
    function get_referrer() {
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }

        return NULL;
    }

}

if (!function_exists('current_url')) {

    /**
     * Gets current url
     * 
     * @return string
     */
    function current_url() {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

}

if (!function_exists('base_dir')) {

    /**
     * Gets base directory
     * 
     * @return string
     */
    function base_dir() {
        return BASE_DIR;
    }

}

if (!function_exists('public_dir')) {

    /**
     * Gets public directory
     * 
     * @return string
     */
    function public_dir() {
        return PUBLIC_DIR;
    }

}

if (!function_exists('uploads_dir')) {

    /**
     * Gets uploads directory
     * 
     * @return string
     */
    function uploads_dir() {
        return UPLOADS_DIR;
    }

}

if (!function_exists('base_url')) {

    /**
     * Gets base url
     * 
     * @return string
     */
    function base_url() {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

}

if (!function_exists('get_config')) {

    /**
     * Gets config value by given key
     * 
     * @return mixed
     */
    function get_config($key) {
        return Config::get($key);
    }

}

if (!function_exists('slugify')) {

    /**
     * Slugifys the string
     * 
     * @param string  $text
     * @return string
     */
    function slugify($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}

if (!function_exists('_message')) {

    /**
     * _message
     * 
     * @param string $subject
     * @param string $params
     * @return string
     */
    function _message($subject, $params) {

        return preg_replace('/{%\d+}/', $params, $subject);
    }

}

if (!function_exists('getallheaders')) {

    /**
     * getallheaders
     * 
     * @return array
     */
    function getallheaders() {
        if (!is_array($_SERVER)) {
            return array();
        }

        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

}

if (!function_exists('get_directory_classes')) {

    /**
     * Gets directory classes
     * 
     * @param string $path
     * @return array
     */
    function get_directory_classes($path) {
        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');

        $class_names = array();

        foreach ($phpFiles as $file) {
            $class = pathinfo($file->getFilename());
            array_push($class_names, $class['filename']);
        }

        return $class_names;
    }

}

if (!function_exists('parse_raw_http_request')) {

    /**
     * Parses raw http request
     * 
     * @param mixed $input
     * @return mixed
     */
    function parse_raw_http_request($input) {

        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        $encoded_data = array();

        if (count($matches) > 0) {
            $boundary = $matches[1];

            $blocks = preg_split("/-+$boundary/", $input);
            array_pop($blocks);

            foreach ($blocks as $id => $block) {
                if (empty($block))
                    continue;

                if (strpos($block, 'application/octet-stream') !== FALSE) {
                    preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
                    if (count($matches) > 0)
                        $encoded_data['files'][$matches[1]] = isset($matches[2]) ? $matches[2] : '';
                }
                else {
                    preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                    if (count($matches) > 0)
                        $encoded_data[$matches[1]] = isset($matches[2]) ? $matches[2] : '';
                }
            }
        }

        return $encoded_data;
    }

}

if (!function_exists('get_user_ip')) {

    /**
     * Gets user IP
     * 
     * @return string
     */
    function get_user_ip() {
        $user_ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }
        return $user_ip;
    }

}