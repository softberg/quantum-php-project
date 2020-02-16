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
 * Class Forget
 * @package Modules\Web\Middlewares
 */
class Forget extends Qt_Middleware
{

    /**
     * Validation rules
     * @var array
     */
    private $ruels = [
        'email' => 'required|valid_email'
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
                redirect(base_url() . '/' . current_lang() . '/forget');
            }

            if (!$this->emailExists($request->get('email'))) {
                session()->setFlash('error', [_message(ExceptionMessages::NON_EXISTING_RECORD, $request->get('email'))]);
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
                if ($user['username'] == $email) {
                    return true;
                }
            }
        }

        return false;
    }

}
