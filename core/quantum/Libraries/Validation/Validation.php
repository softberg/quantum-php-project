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

namespace Quantum\Libraries\Validation;

use GUMP;

/**
 * Validation class
 * 
 * Initialize the database
 * 
 * @package Quantum
 * @subpackage Libraries.Validation
 * @category Libraries
 * @uses \GUMP
 */
class Validation extends \GUMP {

    /**
     * Get class instance
     * 
     * @return object
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Validates by rules
     * 
     * @param array $data
     * @param array $validators
     * @return mixed
     */
    public static function is_valid(array $data, array $validators) {
        $gump = self::get_instance();

        $gump->validation_rules($validators);

        if ($gump->run($data) === false) {
            return $gump->get_errors_array(false);
        } else {
            return true;
        }
    }

    /**
     * Validate uniqueness 
     * 
     * @param mixed $field
     * @param mixed $input
     * @param string $param
     * @return array|null
     */
    public function validate_unique($field, $input, $param) {

        $qtInstance = qt_instance();
        $model = $qtInstance->modelFactory(strtoupper($param));
        
        $model->findOneBy($field, $input[$field]);
        
        if(empty($model->asArray())) {
            return NULL;
        }
        
        return array(
            'field' => $field,
            'value' => null,
            'rule' => __FUNCTION__,
            'param' => $param,
        );
    }

    /**
     * Gets errors list
     * 
     * @param boll $convert_to_string
     * @return array|null
     */
    public function get_errors_array($convert_to_string = false) {
        if (empty($this->errors)) {
            return ($convert_to_string) ? null : array();
        }

        $resp = array();

        foreach ($this->errors as $e) {
            $field = ucwords(str_replace(array('_', '-'), chr(32), $e['field']));
            $param = $e['param'];

            // Let's fetch explicit field names if they exist
            if (array_key_exists($e['field'], self::$fields)) {
                $field = self::$fields[$e['field']];
            }

            switch ($e['rule']) {
                case 'mismatch' :
                    $resp[$field] = "There is no validation rule for $field";
                    break;
                case 'validate_required':
                    $resp[$field] = "The $field field is required";
                    break;
                case 'validate_valid_email':
                    $resp[$field] = "The $field field is required to be a valid email address";
                    break;
                case 'validate_max_len':
                    $resp[$field] = "The $field field needs to be $param or shorter in length";
                    break;
                case 'validate_min_len':
                    $resp[$field] = "The $field field needs to be $param or longer in length";
                    break;
                case 'validate_exact_len':
                    $resp[$field] = "The $field field needs to be exactly $param characters in length";
                    break;
                case 'validate_alpha':
                    $resp[$field] = "The $field field may only contain alpha characters(a-z)";
                    break;
                case 'validate_alpha_numeric':
                    $resp[$field] = "The $field field may only contain alpha-numeric characters";
                    break;
                case 'validate_alpha_dash':
                    $resp[$field] = "The $field field may only contain alpha characters &amp; dashes";
                    break;
                case 'validate_numeric':
                    $resp[$field] = "The $field field may only contain numeric characters";
                    break;
                case 'validate_integer':
                    $resp[$field] = "The $field field may only contain a numeric value";
                    break;
                case 'validate_boolean':
                    $resp[$field] = "The $field field may only contain a true or false value";
                    break;
                case 'validate_float':
                    $resp[$field] = "The $field field may only contain a float value";
                    break;
                case 'validate_valid_url':
                    $resp[$field] = "The $field field is required to be a valid URL";
                    break;
                case 'validate_url_exists':
                    $resp[$field] = "The $field URL does not exist";
                    break;
                case 'validate_valid_ip':
                    $resp[$field] = "The $field field needs to contain a valid IP address";
                    break;
                case 'validate_valid_cc':
                    $resp[$field] = "The $field field needs to contain a valid credit card number";
                    break;
                case 'validate_valid_name':
                    $resp[$field] = "The $field field needs to contain a valid human name";
                    break;
                case 'validate_contains':
                    $resp[$field] = "The $field field needs to contain one of these values: " . implode(', ', $param);
                    break;
                case 'validate_contains_list':
                    $resp[$field] = "The $field field needs to contain a value from its drop down list";
                    break;
                case 'validate_doesnt_contain_list':
                    $resp[$field] = "The $field field contains a value that is not accepted";
                    break;
                case 'validate_street_address':
                    $resp[$field] = "The $field field needs to be a valid street address";
                    break;
                case 'validate_date':
                    $resp[$field] = "The $field field needs to be a valid date";
                    break;
                case 'validate_min_numeric':
                    $resp[$field] = "The $field field needs to be a numeric value, equal to, or higher than $param";
                    break;
                case 'validate_max_numeric':
                    $resp[$field] = "The $field field needs to be a numeric value, equal to, or lower than $param";
                    break;
                case 'validate_min_age':
                    $resp[$field] = "The $field field needs to have an age greater than or equal to $param";
                    break;
                case 'validate_unique':
                    $resp[$field] = "The $field field needs to have unique value";
                    break;
                default:
                    $resp[$field] = "The $field field is invalid";
            }
        }

        return $resp;
    }

}
