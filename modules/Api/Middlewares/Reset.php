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
 * @since 2.5.0
 */

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Reset
 * @package Modules\Api\Middlewares
 */
class Reset extends QtMiddleware
{

    /**
     * @var \Quantum\Libraries\Validation\Validator
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
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Quantum\Exceptions\StopExecutionException
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        list($token) = route_args();

        if (!$this->validator->isValid($request->all())) {
            $response->json([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);

            stop();
        }

        if (!$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [t('validation.nonExistingRecord', 'token')]
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
     * @throws \Exception
     */
    private function checkToken(string $token): bool
    {
        $users = load_users();

        if (is_array($users) && count($users) > 0) {

            foreach ($users as $user) {
                if (isset($user['reset_token']) && $user['reset_token'] == $token) {
                    return true;
                }
            }
        }

        return false;
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
