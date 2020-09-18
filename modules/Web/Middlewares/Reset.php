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

namespace Modules\Web\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Hooks\HookManager;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Reset
 * @package Modules\Web\Middlewares
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
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        list($lang, $token) = current_route_args();

        if ($request->getMethod() == 'POST') { 
            if (!$this->checkToken($token)) {
                session()->setFlash('error', ['password' => [
                        _message(ExceptionMessages::NON_EXISTING_RECORD, 'token')
                    ]
                ]);

                redirect(get_referrer());
            }

            if (!$this->validator->isValid($request->all())) {
                session()->setFlash('error', $this->validator->getErrors());
                redirect(get_referrer());
            }

            if (!$this->confirmPassword($request->get('password'), $request->get('repeat_password'))) {
                session()->setFlash('error', ExceptionMessages::NON_EQUAL_VALUES);
                redirect(get_referrer());
            }
            
        } elseif ($request->getMethod() == 'GET') {
            if (!$this->checkToken($token)) {
                HookManager::call('pageNotFound', $response);
            }
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
