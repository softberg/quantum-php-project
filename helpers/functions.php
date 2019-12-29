<?php

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Loader\Loader;

if (!function_exists('loadUsers')) {
    function loadUsers()
    {
        $loaderSetup = (object)[
            'module' => current_module(),
            'env' => 'base/repositories',
            'fileName' => 'users',
            'exceptionMessage' => ExceptionMessages::CONFIG_FILE_NOT_FOUND
        ];

        $loader = new Loader($loaderSetup);

        return $loader->load();
    }
}