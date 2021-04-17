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

use Quantum\Exceptions\ConfigException;
use Quantum\Middleware\QtMiddleware;
use Quantum\Loader\Loader;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Quantum\Di\Di;
use Closure;

/**
 * Class Activate
 * @package Modules\Api\Middlewares
 */
class Activate extends QtMiddleware
{

    /**
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        list($token) = current_route_args();

        if (!$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [t('validation.nonExistingRecord', 'token')]
            ]);

            stop();
        }

        $request->set('activation_token', $token);

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
        $loaderSetup = (object) [
                    'module' => null,
                    'hierarchical' => true,
                    'env' => 'base' . DS . 'repositories',
                    'fileName' => 'users',
                    'exceptionMessage' => ConfigException::CONFIG_FILE_NOT_FOUND
        ];

        $users = Di::get(Loader::class)->setup($loaderSetup)->load();

        if (is_array($users) && count($users) > 0) {

            foreach ($users as $user) {
                if (isset($user['activation_token']) && $user['activation_token'] == $token) {
                    return true;
                }
            }
        }

        return false;
    }

}
