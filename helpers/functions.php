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
 * @since 1.9.9
 */

use Quantum\Exceptions\ConfigException;
use Quantum\Loader\Loader;
use Quantum\Di\Di;

if (!function_exists('load_users')) {

    /**
     * Load users
     * @return mixed
     * @throws Exception
     */
    function load_users()
    {
        $loaderSetup = (object) [
                    'module' => null,
                    'hierarchical' => true,
                    'env' => 'base' . DS . 'repositories',
                    'fileName' => 'users',
                    'exceptionMessage' => ConfigException::CONFIG_FILE_NOT_FOUND
        ];

        return Di::get(Loader::class)->setup($loaderSetup)->load();
    }

}

if (!function_exists('url_with_lang')) {

    /**
     * Gets the url with selected language
     * @param string $lang
     * @return string
     */
    function url_with_lang($lang)
    {
        $url =  base_url();
        
        if (trim(current_route_uri()) == '/') {
            $url .= '/' . $lang;
        } else {
            $url .= '/' . preg_replace("/" . preg_quote("/" . current_lang(), '/') . "/", $lang, current_route_uri(), 1);
        }
        
        return $url;
    }

}