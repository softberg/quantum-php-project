<?php

namespace Modules\Web\Middlewares;

use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Forget extends Qt_Middleware
{

    private $ruels = [
        'email' => 'required|valid_email'
    ];

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
