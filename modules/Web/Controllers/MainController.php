<?php

namespace Modules\Web\Controllers;

use Quantum\Factory\ModelFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;

class MainController extends Qt_Controller
{

    public $view;

    public function __before(ViewFactory $view)
    {
        $this->view = $view;
        
        $this->view->setLayout('layouts/main');
        
        $this->view->share(['title' => 'Quantum PHP Framework']);
    }

    public function index(ModelFactory $modelFactory)
    {
        $this->view->render('index');
    }

    public function about()
    {
        session()->delete('auth_user');
        $this->view->render('about');
    }

}
