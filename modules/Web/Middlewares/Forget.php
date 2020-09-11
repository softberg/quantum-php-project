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
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Forget
 * @package Modules\Web\Middlewares
 */
class Forget extends QtMiddleware
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

        $this->validator->addRule('email', [
            Rule::set('required'),
            Rule::set('email')
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        if ($request->getMethod() == 'POST') {
            if (!$this->validator->isValid($request->all())) {
                session()->setFlash('error', $this->validator->getErrors());
                redirect(base_url() . '/' . current_lang() . '/forget');
            }

            if (!$this->emailExists($request->get('email'))) {
                session()->setFlash('error', ['email' => [
                        _message(ExceptionMessages::NON_EXISTING_RECORD, $request->get('email'))
                    ]
                ]);
                
                redirect(base_url() . '/' . current_lang() . '/forget');
            }
        }

        return $next($request, $response);
    }

    /**
     * Check for email existence
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    private function emailExists($email)
    {
        $users = $users = loadUsers();

        if (is_array($users) && count($users) > 0) {
            foreach ($users as $user) {
                if ($user['email'] == $email) {
                    return true;
                }
            }
        }

        return false;
    }

}
