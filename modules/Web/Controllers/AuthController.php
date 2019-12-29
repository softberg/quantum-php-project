<?php

namespace Modules\Web\Controllers;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Mailer\Mailer;
use Quantum\Libraries\Lang\Lang;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;

class AuthController extends Qt_Controller
{

    private $layout = 'layouts/auth';
    private $signinView = 'auth/signin';
    private $sigupView = 'auth/signup';
    private $forgetView = 'auth/forget';
    private $resetView = 'auth/reset';
    private $view;

    public function __before(ViewFactory $view)
    {
        $this->view = $view;
        $this->view->setLayout($this->layout);
    }

    public function signin(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            if (auth()->signin($request->get('email'), $request->get('password'), !!$request->get('remember'))) {
                redirect(base_url() . '/' . current_lang());
            }
            session()->setFlash('error', ExceptionMessages::INCORRECT_AUTH_CREDENTIALS);
            redirect(base_url() . '/' . current_lang() . '/signin');
        } else {
            session()->flush();
            $this->view->render($this->signinView);
        }
    }

    public function signout()
    {
        auth()->signout();
        redirect(base_url() . '/' . current_lang());
    }

    public function signup(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            if (auth()->signup($request->all())) {
                redirect(base_url() . '/' . current_lang() . '/signin');
            }
        } else {
            $this->view->render($this->sigupView);
        }
    }

    public function forget(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $mailer = new Mailer();
            $mailer->createSubject(t('common.reset_password'));

            $emailTemplate = base_dir() . DS . 'base' . DS . 'views' . DS . 'email' . DS . 'reset';

            auth()->forget($mailer, $request->get('email'), $emailTemplate);

            session()->setFlash('success', t('common.check_email'));
            redirect(base_url() . '/' . current_lang() . '/forget');
        } else {
            $this->view->render($this->forgetView);
        }
    }

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
