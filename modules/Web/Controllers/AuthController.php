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

namespace Modules\Web\Controllers;

use Quantum\Exceptions\AuthException;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\QtController;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class AuthController
 * @package Modules\Web\Controllers
 */
class AuthController extends QtController
{

    /**
     * Auth layout
     * @var string
     */
    private $layout = 'layouts/auth';

    /**
     * Signin view
     * @var string
     */
    private $signinView = 'auth/signin';

    /**
     * Signup view
     * @var string
     */
    private $sigupView = 'auth/signup';

    /**
     * Forget view
     * @var string
     */
    private $forgetView = 'auth/forget';

    /**
     * Reset view
     * @var string
     */
    private $resetView = 'auth/reset';

    /**
     * Reset view
     * @var string
     */
    private $verifyView = 'auth/verify';

    /**
     * Magic __before
     * @param \Quantum\Factory\ViewFactory $view
     */
    public function __before(ViewFactory $view)
    {
        $view->setLayout($this->layout);
    }

    /**
     * Sign in
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     */
    public function signin(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->isMethod('post')) {
            try {
                $code = auth()->signin($request->get('email'), $request->get('password'), !!$request->get('remember'));

                if (filter_var(config()->get('2SV'), FILTER_VALIDATE_BOOLEAN)) {
                    redirect(base_url() . '/' . current_lang() . '/verify/' . $code);
                } else {
                    redirect(base_url() . '/' . current_lang());
                }
            } catch (AuthException $e) {
                session()->setFlash('error', $e->getMessage());
                redirect(base_url() . '/' . current_lang() . '/signin');
            }
        } else {
            $view->setParam('title', t('common.signin') . ' | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));
            $response->html($view->render($this->signinView));
        }
    }

    /**
     * Sign out
     */
    public function signout()
    {
        auth()->signout();
        redirect(base_url() . '/' . current_lang());
    }

    /**
     * Sign up
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     */
    public function signup(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->isMethod('post')) {
            if (auth()->signup($request->all())) {
                redirect(base_url() . '/' . current_lang() . '/signin');
            }
        } else {
            $view->setParam('title', t('common.signup') . ' | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));
            $response->html($view->render($this->sigupView));
        }
    }

    /**
     * Activate
     * @param \Quantum\Http\Request $request
     */
    public function activate(Request $request)
    {
        auth()->activate($request->get('activation_token'));
        redirect(base_url() . '/' . current_lang() . '/signin');
    }

    /**
     * Forget
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     */
    public function forget(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->isMethod('post')) {
            auth()->forget($request->get('email'));

            session()->setFlash('success', t('common.check_email'));
            redirect(base_url() . '/' . current_lang() . '/forget');
        } else {
            $view->setParam('title', t('common.forget_password') . ' | ' . config()->get('app_name'));
            $view->setParam('langs', config()->get('langs'));
            $response->html($view->render($this->forgetView));
        }
    }

    /**
     * Reset
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     */
    public function reset(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->isMethod('post')) {
            auth()->reset($request->get('reset_token'), $request->get('password'));
            redirect(base_url() . '/' . current_lang() . '/signin');
        } else {
            $view->setParams([
                'title' => t('common.reset_password') . ' | ' . config()->get('app_name'),
                'langs' => config()->get('langs'),
                'reset_token' => $request->get('reset_token')
            ]);

            $response->html($view->render($this->resetView));
        }
    }

    /**
     * Verify OTP
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Quantum\Factory\ViewFactory $view
     */
    public function verify(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->isMethod('post')) {
            try {
                auth()->verifyOtp($request->get('otp'), $request->get('code'));
                redirect(base_url() . '/' . current_lang());
            } catch (AuthException $e) {
                session()->setFlash('error', $e->getMessage());
                redirect(base_url() . '/' . current_lang() . '/verify/' . $request->get('code'));
            }
        } else {
            $view->setParams([
                'title' => t('common.2sv') . ' | ' . config()->get('app_name'),
                'langs' => config()->get('langs'),
                'code' => $request->getSegment(3)
            ]);

            $response->html($view->render($this->verifyView));
        }
    }

    /**
     * Resend OTP
     * @param \Quantum\Http\Request $request
     */
    public function resend(Request $request)
    {
        if (!$request->getSegment(3)) {
            redirect(base_url() . '/' . current_lang() . '/signin');
        }

        try {
            $code = auth()->resendOtp($request->getSegment(3));
            redirect(base_url() . '/' . current_lang() . '/verify/' . $code);
        } catch (AuthException $e) {
            redirect(base_url() . '/' . current_lang() . '/signin');
        }
    }

}
