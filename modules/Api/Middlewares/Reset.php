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

namespace Modules\Api\Middlewares;

use Closure;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Shared\Models\User;

/**
 * Class Reset
 * @package Modules\Api\Middlewares
 */
class Reset extends QtMiddleware
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

        $this->validator->addRules([
            'password' => [
                Rule::set('required'),
                Rule::set('minLen', 6)
            ],
            'repeat_password' => [
                Rule::set('required'),
                Rule::set('minLen', 6)
            ]
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
        $token = route_param('token');

        if (!$token || !$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [t('validation.nonExistingRecord', 'token')]
            ]);

            stop();
        }

        if (!$this->validator->isValid($request->all())) {
            $response->json([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);

            stop();
        }

        if (!$this->confirmPassword($request->get('password'), $request->get('repeat_password'))) {
            $response->json([
                'status' => 'error',
                'message' => t('validation.nonEqualValues')
            ]);

            stop();
        }

        $request->set('reset_token', $token);

        return $next($request, $response);
    }

    /**
     * Check token
     * @param string $token
     * @return bool
     */
    private function checkToken(string $token): bool
    {
        $userModel = ModelFactory::get(User::class);
        return !empty($userModel->findOneBy('reset_token', $token)->asArray());
    }

    /**
     * Checks the password and repeat password
     * @param string $newPassword
     * @param string $repeatPassword
     * @return bool
     */
    private function confirmPassword(string $newPassword, string $repeatPassword): bool
    {
        return $newPassword == $repeatPassword;
    }

}
