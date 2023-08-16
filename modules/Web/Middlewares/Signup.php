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
 * @since 2.8.0
 */

namespace Modules\Web\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Factory\ModelFactory;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Shared\Models\User;
use Closure;

/**
 * Class Signup
 * @package Modules\Web\Middlewares
 */
class Signup extends QtMiddleware
{

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Class constructor
     * @throws \Exception
     */
    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->addValidation('uniqueUser', function ($value) {
            $userModel = ModelFactory::get(User::class);
            return empty($userModel->findOneBy('email', $value)->asArray());
        });

        $this->validator->addRules([
            'email' => [
                Rule::set('required'),
                Rule::set('email'),
                Rule::set('uniqueUser')
            ],
            'password' => [
                Rule::set('required'),
                Rule::set('minLen', 6)
            ],
            'firstname' => [
                Rule::set('required')
            ],
            'lastname' => [
                Rule::set('required')
            ],
            'captcha' => [
                Rule::set('required'),
                Rule::set('captcha')
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        if ($request->isMethod('post')) {

            if($request->has('g-recaptcha-response')) {
                $request->set('captcha', $request->get('g-recaptcha-response'));
                $request->delete('g-recaptcha-response');
            }

            if ($request->has('h-captcha-response')) {
                $request->set('captcha', $request->get('h-captcha-response'));
                $request->delete('h-captcha-response');
            }

            if (!$this->validator->isValid($request->all())) {
                session()->setFlash('error', $this->validator->getErrors());
                redirectWith(base_url(true) . '/' . current_lang() . '/signup', $request->all());
            }

            if ($request->has('captcha')) {
                $request->delete('captcha');
            }
        }

        return $next($request, $response);
    }

}
