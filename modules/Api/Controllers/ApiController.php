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
 * @since 2.8.0
 */

namespace Modules\Api\Controllers;

use Quantum\Mvc\QtController;
use OpenApi\Annotations as OA;

/**
 * Class ApiController
 * @package Modules\Api\Controllers
 */
class ApiController extends QtController
{

    /**
     * @OA\Info(
     *      title="Quantum API documentation",
     *      version="1.0.0",
     *      description="
     *Quantum Documentation: https://quantum.softberg.org/en/docs/v1/overview"
     *      ),  
     *      @OA\SecurityScheme(
     *          securityScheme="bearer_token",
     *          type="apiKey",
     *          name="Authorization",
     *          in="header"
     * )
     * CSRF verification
     * @var bool
     */
    public $csrfVerification = false;

}
