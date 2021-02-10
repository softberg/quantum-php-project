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
use Quantum\Libraries\Mailer\Mailer;
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
     * @param ViewFactory $view
     */
    public function __before(ViewFactory $view)
    {
        $view->setLayout($this->layout);
    }

    /**
     * Sign in
     * @param Request $request
     * @param Response $response
     * @param ViewFactory $view
     * @throws \Exception
     */
    public function signin(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->getMethod() == 'POST') {
            try {
                $mailer = new Mailer();
                $mailer->setSubject(t('common.otp'));
                $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'verification');

                $code = auth()->signin($mailer, $request->get('email'), $request->get('password'), !!$request->get('remember'));

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
     * @throws \Exception
     */
    public function signout()
    {
        auth()->signout();
        redirect(base_url() . '/' . current_lang());
    }

    /**
     * Sign up
     * @param Request $request
     * @param Response $response
     * @param ViewFactory $view
     */
    public function signup(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->getMethod() == 'POST') {

            $mailer = new Mailer();
            $mailer->setSubject(t('common.activate_account'));
            $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'activate');

            if (auth()->signup($mailer, $request->all())) {
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
     * @param Request $request
     */
    public function activate(Request $request)
    {
        auth()->activate($request->get('activation_token'));
        redirect(base_url() . '/' . current_lang() . '/signin');
    }

    /**
     * Forget
     * @param Request $request
     * @throws \Exception
     */
    public function forget(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->getMethod() == 'POST') {

            $mailer = new Mailer();
            $mailer->setSubject(t('common.reset_password'));
            $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'reset');

            auth()->forget($mailer, $request->get('email'));

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
     * @param Request $request
     */
    public function reset(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->getMethod() == 'POST') {
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
     * Verify
     * @param Request $request
     * @param Response $response
     * @param ViewFactory $view
     */
    public function verify(Request $request, Response $response, ViewFactory $view)
    {
        if ($request->getMethod() == 'POST') {
            try {

                auth()->verify($request->get('otp'),$request->get('hash'));
                redirect(base_url() . '/' . current_lang());
            } catch (AuthException $e) {
                session()->setFlash('error', $e->getMessage());
                redirect(base_url() . '/' . current_lang() . '/verify');
            }
        } else {

            $view->setParams([
                'title' => t('common.verify') . ' | ' . config()->get('app_name'),
                'langs' => config()->get('langs'),
                'hash' => $request->getSegment(3)
            ]);


            $response->html($view->render($this->verifyView));
        }
    }

    /**
     * Resend
     * @param Request $request
     */

    public function resend(Request $request)
    {
        if ($request->getSegment(3)){

            $mailer = new Mailer();
            $mailer->setSubject(t('common.otp'));
            $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'verification');

            $code = auth()->resendOtp($mailer, $request->getSegment(3));

            if ($code){
                redirect(base_url() . '/' . current_lang() . '/verify/' . $code);
            }
        } else {

            redirect(base_url() . '/' . current_lang() . '/signin');
        }
    }
}
