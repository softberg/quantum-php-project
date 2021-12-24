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

namespace Modules\Web\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Signup
 * @package Modules\Web\Middlewares
 */
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

        $users = load_users();

        $this->validator->addValidation('uniqueUser', function ($value, $users) {
            if (is_array($users) && count($users) > 0) {
                foreach ($users as $user) {
                    if ($user['email'] == $value) {
                        return false;
                    }
                }
            }

            return true;
        });

        $this->validator->addRules([
            'email' => [
                Rule::set('required'),
                Rule::set('email'),
                Rule::set('uniqueUser', $users)
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
    public function apply(Request $request, Response $response, Closure $next)
    {
        if ($request->isMethod('post')) {

            if (!$this->validator->isValid($request->all())) {
                session()->setFlash('error', $this->validator->getErrors());
                redirectWith(base_url() . '/' . current_lang() . '/signup', $request->all());
            }
        }

        return $next($request, $response);
    }

}
