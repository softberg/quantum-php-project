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

namespace Modules\Web\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Editor
 * @package Modules\Web\Middlewares
 */
class Editor extends QtMiddleware
{

    /**
     * Validator object
     * @var Validator
     */
    private $validator;
    private $request;
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->validator = new Validator();
        if($this->request->hasFile('image')){
            $this->validator->addRules([
                'image' => [
                    Rule::set('fileSize', 2097152),
                    Rule::set('fileExtension',['jpeg','png','jpg','gif']),
                ]
            ]);  
        }

        $this->validator->addRules([
        
            'title' => [
                Rule::set('required'),
                Rule::set('minLen', 10)
            ],
            'content' => [
                Rule::set('required'),
                Rule::set('minLen', 10),
                Rule::set('maxLen', 500),
            ],
           
           
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        if (auth()->user()->getFieldValue('role') != 'admin' && auth()->user()->getFieldValue('role') != 'editor') {
            redirect(base_url() . '/' . current_lang());
        }

        if ($request->isMethod('post')) {
            if (!$this->validator->isValid($request->all())) {
                session()->setFlash('error', $this->validator->getErrors());
                redirectWith(get_referrer(), $request->all());
            }
        }

        return $next($request, $response);
    }

}
