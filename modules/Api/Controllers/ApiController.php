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
 * @since 2.7.0
 */

namespace Modules\Api\Controllers;

use Quantum\Mvc\QtController;
use Quantum\Http\Response;

/**
 * Class ApiController
 * @package Modules\Api\Controllers
 */
class ApiController extends QtController
{

    /**
     * CSRF verification
     * @var bool
     */
    public $csrfVerification = false;

    public function __construct()
    {
        Response::setHeader('Access-Control-Allow-Origin', '*');
        Response::setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        Response::setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

}
