<?php

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Loader\Loader;
use Quantum\Http\Request;

class Signup extends Qt_Middleware
{

    private $ruels = [
        'username' => 'required|valid_email',
        'password' => 'required|min_len,6',
        'firstname' => 'required',
        'lastname' => 'required',
    ];

    public function apply(Request $request, Response $response, \Closure $next)
    {
        $validated = Validation::is_valid($request->all(), $this->ruels);

        if ($validated !== true) {
            $response->json([
                'status' => 'error',
                'message' => $validated
            ]);
        }

        if (!$this->isUnique($request->all())) {
            $response->json([
                'status' => 'error',
                'message' => [_message(ExceptionMessages::NON_UNIQUE_VALUE, 'username')]
            ]);
        }

        return $next($request, $response);
    }

    private function isUnique($userData)
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
                if ($user['username'] == $userData['username']) {
                    return false;
                }
            }
        }

        return true;
    }

}
