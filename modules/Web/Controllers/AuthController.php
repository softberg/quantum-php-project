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

namespace Modules\Web\Controllers;

use Quantum\Exceptions\AuthException;
use Quantum\Libraries\Mailer\Mailer;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;

/**
 * Class AuthController
 * @package Modules\Web\Controllers
 */
class AuthController extends Qt_Controller
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
     * View
     * @var ViewFactory
     */
    private $view;

    /**
     * Magic __before
     * @param ViewFactory $view
     */
    public function __before(ViewFactory $view)
    {
        $this->view = $view;
        
        $this->view->setLayout($this->layout);
        
        $this->view->share(['title' => 'Quantum PHP Framework']);
    }

    /**
     * Sign in
     * @param Request $request
     * @throws \Exception
     */
    public function signin(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            try {
                if (auth()->signin($request->get('email'), $request->get('password'), !!$request->get('remember'))) {
                    redirect(base_url() . '/' . current_lang());
                }
            } catch (AuthException $e) {
                session()->setFlash('error', $e->getMessage());
                redirect(base_url() . '/' . current_lang() . '/signin');
            }
        } else {
            $this->view->render($this->signinView);
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
     */
    public function signup(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $mailer = new Mailer();
            $mailer->createSubject(t('common.activate_account'));
            $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'activate');

            if (auth()->signup($mailer, $request->all())) {
                redirect(base_url() . '/' . current_lang() . '/signin');
            }
        } else {
            $this->view->render($this->sigupView);
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
    public function forget(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $mailer = new Mailer();
            $mailer->createSubject(t('common.reset_password'));
            $mailer->setTemplate(base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'reset');

            auth()->forget($mailer, $request->get('email'), $emailTemplate);

            session()->setFlash('success', t('common.check_email'));
            redirect(base_url() . '/' . current_lang() . '/forget');
        } else {
            $this->view->render($this->forgetView);
        }
    }

    /**
     * Reset
     * @param Request $request
     */
    public function reset(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            auth()->reset($request->get('reset_token'), $request->get('password'));
            redirect(base_url() . '/' . current_lang() . '/signin');
        } else {
            $this->view->render($this->resetView, ['reset_token' => $request->get('reset_token')]);
        }
    }

}
