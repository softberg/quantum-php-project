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

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Loader\Loader;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Reset
 * @package Modules\Api\Middlewares
 */
class Reset extends QtMiddleware
{

    /**
     * Validator object
     * @var Validator
     */
    private $validator;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->addRule('password', [
            Rule::set('required'),
            Rule::set('minLen', 6)
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        list($token) = current_route_args();

        if (!$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]
            ]);
            
            stop();
        }

        if (!$this->confirmPassword($request->get('password'), $request->get('repeat_password'))) {
            $response->json([
                'status' => 'error',
                'message' => ExceptionMessages::NON_EQUAL_VALUES
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

        $request->set('reset_token', $token);

        return $next($request, $response);
    }

    
    /**
     * Check token
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    private function checkToken($token)
    {
        $users = loadUsers();

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
    private function confirmPassword($newPassword, $repeatPassword)
    {
        return $newPassword == $repeatPassword;
    }

}
