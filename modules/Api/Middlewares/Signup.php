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
 * @since 2.6.0
 */

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Factory\ModelFactory;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Shared\Models\User;
use Quantum\Di\Di;

class Signup extends QtMiddleware
{

    /**
     * @var \Quantum\Libraries\Validation\Validator
     */
    private $validator;

    /**
     * Class constructor
     * @throws \Exception
     */
    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->addValidation('uniqueUser', function ($value) {
            $modelFactory = Di::get(ModelFactory::class);
            $userModel = $modelFactory->get(User::class);

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
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!$this->validator->isValid($request->all())) {
            $response->json([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);

            stop();
        }

        return $next($request, $response);
    }

}
