<?php

namespace Modules\Main\Controllers;

use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Quantum\Libraries\Lang\Lang;
use Modules\Main\Models\User;

class MainController extends Qt_Controller {

    public function __construct() {

        $this->setLayout('layouts/main');
    }

    public function __before() {
        $lang = $this->getSegment(1);
        Lang::set($lang);
    }

    public function indexAction() {
        $this->render('index');
    }

}
