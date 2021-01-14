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

use Quantum\Factory\ViewFactory;
use Quantum\Mvc\QtController;
use Quantum\Http\Response;

/**
 * Class MainController
 * @package Modules\Web\Controllers
 */
class MainController extends QtController
{

    /**
     * Magic __before
     * @param ViewFactory $view
     */
    public function __before(ViewFactory $view)
    {
        $view->setLayout('layouts/landing');
    }

    /**
     * Default index page
     * @param Response $response
     * @param ViewFactory $view
     */
    public function index(Response $response, ViewFactory $view)
    {
        $view->setParam('title', 'Welcome | ' . config()->get('app_name'));
        $view->setParam('langs', config()->get('langs'));
        $response->html($view->render('index'));
    }

    /**
     * About page
     * @param Response $response
     * @param ViewFactory $view
     */
    public function about(Response $response, ViewFactory $view)
    {
        $view->setParam('title', 'About | ' . config()->get('app_name'));
        $view->setParam('langs', config()->get('langs'));
        $response->html($view->render('about'));
    }

}
