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
 * @since 2.5.0
 */

namespace Modules\Api\Controllers;

use Quantum\Exceptions\AuthException;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class AuthController
 * @package Modules\Api\Controllers
 */
class AuthController extends ApiController
{

    /**
     * Status error
     */
    const STATUS_ERROR = 'error';

    /**
     * Status success
     */
    const STATUS_SUCCESS = 'success';

    /**
     * Sign in action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function signin(Request $request, Response $response)
    {
        if ($request->isMethod('post')) {
            try {
                $code = auth()->signin($request->get('username'), $request->get('password'));

                if (filter_var(config()->get('2SV'), FILTER_VALIDATE_BOOLEAN)) {
                    $response->json([
                        'status' => self::STATUS_SUCCESS,
                        'code' => $code
                    ]);
                } else {
                    $response->json([
                        'status' => self::STATUS_SUCCESS
                    ]);
                }
            } catch (AuthException $e) {
                $response->json([
                    'status' => self::STATUS_ERROR,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Sign out action
     * @param \Quantum\Http\Response $response
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function signout(Response $response)
    {
        if (auth()->signout()) {
            $response->json([
                'status' => self::STATUS_SUCCESS
            ]);
        } else {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => t('validation.unauthorizedRequest')
            ]);
        }
    }

    /**
     * Sign up action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function signup(Request $request, Response $response)
    {
        if (auth()->signup($request->all())) {
            $response->json([
                'status' => self::STATUS_SUCCESS
            ]);
        }
    }

    /**
     * Activate action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function activate(Request $request, Response $response)
    {
        auth()->activate($request->get('activation_token'));

        $response->json([
            'status' => self::STATUS_SUCCESS,
            'message' => t('common.account_activated')
        ]);
    }

    /**
     * Forget action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function forget(Request $request, Response $response)
    {
        auth()->forget($request->get('email'));

        $response->json([
            'status' => self::STATUS_SUCCESS,
            'message' => t('common.check_email')
        ]);
    }

    /**
     * Reset action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function reset(Request $request, Response $response)
    {
        auth()->reset($request->get('reset_token'), $request->get('password'));
        $response->json([
            'status' => self::STATUS_SUCCESS
        ]);
    }

    /**
     * Verify action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function verify(Request $request, Response $response)
    {
        try {
            auth()->verifyOtp($request->get('otp'), $request->get('code'));

            $response->json([
                'status' => self::STATUS_SUCCESS
            ]);
        } catch (AuthException $e) {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Resend action
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function resend(Request $request, Response $response)
    {
        try {
            $code = auth()->resendOtp($request->get('code'));

            $response->json([
                'status' => self::STATUS_SUCCESS,
                'code' => $code
            ]);
        } catch (AuthException $e) {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

}
