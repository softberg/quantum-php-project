<?php

namespace Modules\Api\Controllers;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Lang\Lang;
use Quantum\Libraries\Mailer\Mailer;
use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Response;
use Quantum\Http\Request;

class AuthController extends Qt_Controller
{

    public $csrfVerification = false;

    public function signin(Request $request, Response $response)
    {
        if ($request->getMethod() == 'POST') {

            $tokens = auth()->signin($request->get('username'), $request->get('password'));
            if ($tokens) {
                $response->json([
                    'status' => 'success',
                    'data' => $tokens
                ]);
            } else {
                $response->json([
                    'status' => 'error',
                    'message' => ExceptionMessages::INCORRECT_AUTH_CREDENTIALS
                ]);
            }
        }
    }

    public function signout(Response $response)
    {
        if (auth()->signout()) {
            $response->json([
                'status' => 'success'
            ]);
        } else {
            $response->json([
                'status' => 'error',
                'message' => ExceptionMessages::UUAUTHORIZED_REQUEST
            ]);
        }

    }

    public function signup(Request $request, Response $response)
    {
        if (auth()->signup($request->all())) {
            $response->json([
                'status' => 'success'
            ]);
        }

    }

    public function forget(Request $request, Response $response)
    {
        $mailer = new Mailer();
        $mailer->createSubject(t('common.reset_password'));

        $emailTemplate = base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'reset';

        auth()->forget($mailer, $request->get('email'), $emailTemplate);

        $response->json([
            'status' => 'success',
            'message' => t('common.check_email')
        ]);
    }

    public function reset(Request $request, Response $response)
    {
        auth()->reset($request->get('reset_token'), $request->get('password'));
        $response->json([
            'status' => 'success'
        ]);

    }

}
