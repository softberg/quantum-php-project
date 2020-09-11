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
 * @since 2.0.0
 */

namespace Modules\Api\Controllers;

use Quantum\Mvc\QtController;

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

}
