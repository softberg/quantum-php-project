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

                $mailer = new Mailer();
                $mailer->setSubject('Verification code');
                $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'verification');

                $code = auth()->signin($mailer, $request->get('username'), $request->get('password'));

                if (filter_var(config()->get('2SV'), FILTER_VALIDATE_BOOLEAN)) {
                    $response->json([
                        'status' => 'success',
                        'otp_token' => $code
                    ]);
                } else {
                    $response->json([
                        'status' => 'success'
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
        $mailer->setSubject(t('common.activate_account'));
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
        $mailer->setSubject(t('common.reset_password'));
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

    /**
     * Verify
     * @param Request $request
     * @param Response $response
     */

    public function verify(Request $request, Response $response)
    {
        try {

            auth()->verify($request->get('otp'), $request->get('otp_token'));

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

    /**
     * Resend
     * @param Request $request
     */

    public function resend(Request $request, Response $response)
    {

        try {

            $mailer = new Mailer();
            $mailer->setSubject(t('common.otp'));
            $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'verification');
            $code = auth()->resendOtp($mailer, $request->get('otp_token'));
            $response->json([
                'status' => 'success',
                'otp_token' => $code

            ]);

        } catch (AuthException $e) {
            $response->json([
            'status' => 'error',
            'message' => $e->getMessage()
            ]);
        }

    }
}
