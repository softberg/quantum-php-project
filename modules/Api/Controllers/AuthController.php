<?php

namespace Modules\Api\Controllers;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Exceptions\AuthException;
use Quantum\Libraries\Mailer\Mailer;
use Quantum\Http\Response;
use Quantum\Http\Request;

class AuthController extends ApiController
{

    public function signin(Request $request, Response $response)
    {
        if ($request->getMethod() == 'POST') {
            try {
                $tokens = auth()->signin($request->get('username'), $request->get('password'));
                if ($tokens) {
                    $response->json([
                        'status' => 'success',
                        'data' => $tokens
                    ]);
                }
            } catch (AuthException $e) {
                $response->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
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
                'message' => ExceptionMessages::UNAUTHORIZED_REQUEST
            ]);
        }
    }

    public function signup(Request $request, Response $response)
    {
        $mailer = new Mailer();
        $mailer->createSubject(t('common.activate_account'));
        $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'activate');

        if (auth()->signup($mailer, $request->all())) {
            $response->json([
                'status' => 'success'
            ]);
        }
    }

    public function activate(Request $request, Response $response)
    {
        auth()->activate($request->get('activation_token'));

        $response->json([
            'status' => 'success',
            'message' => t('common.account_activated')
        ]);
    }

    public function forget(Request $request, Response $response)
    {
        $mailer = new Mailer();
        $mailer->createSubject(t('common.reset_password'));
        $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'reset');

        auth()->forget($mailer, $request->get('email'));

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
