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

namespace Modules\Api\Controllers;

use Quantum\Mvc\Qt_Controller;

/**
 * Class ApiController
 * @package Modules\Api\Controllers
 */
class ApiController extends Qt_Controller
{
    /**
     * CSRF verification
     * @var bool
     */
    public $csrfVerification = false;

}
