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
 * @since 1.9.9
 */

namespace Modules\Web\Middlewares;

use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Signup
 * @package Modules\Web\Middlewares
 */
class Signup extends Qt_Middleware
{

    /**
     * Validation rules
     * @var array
     */
    private $ruels = [
        'username' => 'required|valid_email',
        'password' => 'required|min_len,6',
        'firstname' => 'required',
        'lastname' => 'required',
    ];

    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if ($request->getMethod() == 'POST') {
            $validated = Validation::is_valid($request->all(), $this->ruels);

            if ($validated !== true) {
                session()->setFlash('error', $validated);
                redirectWith(base_url() . '/signup', $request->all());
            }

            if (!$this->isUnique($request->all())) {
                session()->setFlash('error', [_message(ExceptionMessages::NON_UNIQUE_VALUE, 'username')]);
                redirectWith(base_url() . '/signup', $request->all());
            }
        }

        return $next($request, $response);
    }

    /**
     * Check for uniqueness
     * @param array $userData
     * @return bool
     * @throws \Exception
     */
    private function isUnique($userData)
    {
        $users = loadUsers();

        if (is_array($users) && count($users) > 0) {
            foreach ($users as $user) {
                if ($user['username'] == $userData['username']) {
                    return false;
                }
            }
        }

        return true;
    }

}
