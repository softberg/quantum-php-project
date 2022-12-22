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
 * @since 2.9.0
 */

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Factory\ModelFactory;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Shared\Models\User;
use Closure;

/**
 * Class Signup
 * @package Modules\Api
 */
class Signup extends QtMiddleware
{

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->addValidation('uniqueUser', function ($value) {
            $userModel = ModelFactory::get(User::class);
            return empty($userModel->findOneBy('email', $value)->asArray());
        });

        $this->validator->addRules([
            'email' => [
                Rule::set('required'),
                Rule::set('email'),
                Rule::set('uniqueUser')
            ],
            'password' => [
                Rule::set('required'),
                Rule::set('minLen', 6)
            ],
            'firstname' => [
                Rule::set('required')
            ],
            'lastname' => [
                Rule::set('required')
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        if (!$this->validator->isValid($request->all())) {
            $response->json([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ], 422);

            stop();
        }

        return $next($request, $response);
    }

}
