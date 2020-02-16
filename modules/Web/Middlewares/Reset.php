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
use Quantum\Hooks\HookManager;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Reset
 * @package Modules\Web\Middlewares
 */
class Reset extends Qt_Middleware
{

    /**
     * Validation rules
     * @var array
     */
    private $ruels = [
        'password' => 'required|min_len,6'
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
        list($lang, $token) = current_route_args();

        if ($request->getMethod() == 'POST') {
            if (!$this->checkToken($token)) {
                session()->setFlash('error', [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]);
                redirect(get_referrer());
            }

            $validated = Validation::is_valid($request->all(), $this->ruels);
            if ($validated !== true) {
                session()->setFlash('error', $validated);
                redirect(get_referrer());
            }
        } elseif ($request->getMethod() == 'GET') {
            if (!$this->checkToken($token)) {
                HookManager::call('pageNotFound');
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

}
