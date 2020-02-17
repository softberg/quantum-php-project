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

namespace Modules\Api\Controllers;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Exceptions\AuthException;
use Quantum\Libraries\Mailer\Mailer;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class AuthController
 * @package Modules\Api\Controllers
 */
class AuthController extends ApiController
{
    /**
     * Sign in
     * @param Request $request
     * @param Response $response
     */
    public function signin(Request $request, Response $response)
    {
        if ($request->getMethod() == 'POST') {
            try {
                auth()->signin($request->get('username'), $request->get('password'));

                $response->json([
                    'status' => 'success'
                ]);
            } catch (AuthException $e) {
                $response->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Sign out
     * @param Response $response
     * @throws \Exception
     */
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

    /**
     * Sign up
     * @param Request $request
     * @param Response $response
     */
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

    /**
     * Activate
     * @param Request $request
     * @param Response $response
     */
    public function activate(Request $request, Response $response)
    {
        auth()->activate($request->get('activation_token'));

        $response->json([
            'status' => 'success',
            'message' => t('common.account_activated')
        ]);
    }

    /**
     * Forget
     * @param Request $request
     * @param Response $response
     */
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

    /**
     * Reset
     * @param Request $request
     * @param Response $response
     */
    public function reset(Request $request, Response $response)
    {
        auth()->reset($request->get('reset_token'), $request->get('password'));
        $response->json([
            'status' => 'success'
        ]);
    }

}
