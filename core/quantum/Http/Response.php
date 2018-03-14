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
use Quantum\Mvc\Qt_Controller;

/**
 * Response Class
 * 
 * Sends response from server
 * 
 * @package Quantum
 * @subpackage Http
 * @category Http
 */
class Response extends HttpResponse {

    /**
     * Error Page
     * 
     * Outputs error page
     * 
     * @param string $file
     * @param integer $code
     * @param string $modulePath
     * @param mixed $params
     * @return void
     */
    public static function errorPage($file, $code, $modulePath, $params = NULL) {
        parent::setStatus($code);
        
        qt_instance()->output('errors/' . $file, $modulePath, $params);
        exit;
    }

    /**
     * JSON
     * 
     * Sends JSON response
     * 
     * @param array $arr
     * @param integer $status
     * @return string
     */
    public static function json(array $arr, $status = NULL) {
        if ($status) {
            parent::setStatus($status);
        }
        parent::setContentType('application/json');
        return json_encode($arr);
    }

    /**
     * JSON output
     * 
     * Outputs JSON response
     * 
     * @param array $arr
     * @param integer $status
     */
    public static function jsonOutput(array $arr, $status = NULL) {
        echo self::json($arr, $status);
    }

    /**
     * XML
     * 
     * Sends XML response
     * 
     * @param array $arr
     * @return string
     */
    public static function xml(array $arr) {
        parent::setContentType('application/xml');

        $simpleXML = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
        self::arrayToXML($arr, $simpleXML);

        return $simpleXML->asXML();
    }

    /**
     * ArrayToXML
     * 
     * Transforms array to XML
     * 
     * @param array $arr
     * @param object $simpleXML
     * @return void
     */
    private function arrayToXML(array $arr, &$simpleXML) {
        foreach ($arr as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            if (is_array($value)) {
                $subnode = $simpleXML->addChild($key);
                self::arrayToXML($value, $subnode);
            } else {
                $simpleXML->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

}
