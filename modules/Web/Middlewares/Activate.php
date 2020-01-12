<?php

namespace Modules\Web\Middlewares;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Hooks\HookManager;
use Quantum\Loader\Loader;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Activate extends Qt_Middleware
{

    public function apply(Request $request, Response $response, \Closure $next)
    {
        list($lang, $token) = current_route_args();

        if (!$this->checkToken($token)) {
            HookManager::call('pageNotFound');
        }

        $request->set('activation_token', $token);

        return $next($request, $response);
    }

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
                if (isset($user['activation_token']) && $user['activation_token'] == $token) {
                    return true;
                }
            }
        }

        return false;
    }

}
