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

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Loader\Loader;
use Quantum\Http\Request;

/**
 * Class Forget
 * @package Modules\Api\Middlewares
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
                $response->json([
                    'status' => 'error',
                    'message' => $validated
                ]);
            }

            if (!$this->emailExists($request->get('email'))) {
                $response->json([
                    'status' => 'error',
                    'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, $request->get('email'))]
                ]);
            }
        }

        return $next($request, $response);
    }

    /**
     * Checks email for existence
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    private function emailExists($email)
    {
        $loaderSetup = (object)[
            'module' => current_module(),
            'env' => 'base/repositories',
            'fileName' => 'users',
            'exceptionMessage' => ExceptionMessages::CONFIG_FILE_NOT_FOUND
        ];

        $loader = new Loader($loaderSetup);

        $users = $loader->load();

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
