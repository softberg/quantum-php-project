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

use Quantum\Factory\ModelFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;

/**
 * Class MainController
 * @package Modules\Web\Controllers
 */
class MainController extends Qt_Controller
{

    /**
     * View
     * @var ViewFactory
     */
    public $view;

    /**
     * Magic __before
     * @param ViewFactory $view
     */
    public function __before(ViewFactory $view)
    {
        $this->view = $view;
        
        $this->view->setLayout('layouts/main');
        
        $this->view->share(['title' => 'Quantum PHP Framework']);
    }

    /**
     * Default index page
     * @param ModelFactory $modelFactory
     */
    public function index(ModelFactory $modelFactory)
    {
        $this->view->render('index');
    }

    /**
     * About page
     * @throws \Exception
     */
    public function about()
    {
        session()->delete('auth_user');
        $this->view->render('about');
    }

}
