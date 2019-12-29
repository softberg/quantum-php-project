<?php

namespace Modules\Web\Controllers;

use Base\Models\AuthModel;
use Quantum\Factory\ModelFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Libraries\Lang\Lang;
use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;

class MainController extends Qt_Controller
{

    public $view;

    public function __before(ViewFactory $view)
    {
        $lang = Request::getSegment(1);
        Lang::init($lang);

        $this->view = $view;
        $this->view->setLayout('layouts/main');
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
