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
use Quantum\Loader\Loader;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Reset
 * @package Modules\Api\Middlewares
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
        list($token) = current_route_args();

        if (!$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]
            ]);
        }

        $validated = Validation::is_valid($request->all(), $this->ruels);
        if ($validated !== true) {
            $response->json([
                'status' => 'error',
                'message' => $validated
            ]);
        }

        $request->set('reset_token', $token);

        return $next($request, $response);
    }

    /**
     * Cjeck token
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    private function checkToken($token)
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
                if (isset($user['reset_token']) && $user['reset_token'] == $token) {
                    return true;
                }
            }
        }

        return false;
    }

}
