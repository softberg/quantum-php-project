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
 * @since 2.9.0
 */

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Editor
 * @package Modules\Api
 */
class Editor extends QtMiddleware
{

    /**
     * Roles
     */
    const ROLES = ['admin', 'editor'];

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Class constructor
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->validator = new Validator();

        if ($request->hasFile('image')) {
            $this->validator->addRules([
                'image' => [
                    Rule::set('fileSize', 2 * pow(1024, 2)),
                    Rule::set('fileExtension', ['jpeg', 'jpg', 'png']),
                ]
            ]);
        }

        $this->validator->addRules([
            'title' => [
                Rule::set('required'),
                Rule::set('minLen', 10),
                Rule::set('maxLen', 50),
            ],
            'content' => [
                Rule::set('required'),
                Rule::set('minLen', 10),
                Rule::set('maxLen', 1000),
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
        if (!in_array(auth()->user()->role, self::ROLES)) {
            $response->json([
                'status' => 'error',
                'message' => t('validation.unauthorizedRequest')
            ], 401);

            stop();
        }

        if ($request->isMethod('post') || $request->isMethod('put')) {
            if (!$this->validator->isValid($request->all())) {
                $response->json([
                    'status' => 'error',
                    'message' => $this->validator->getErrors()
                ], 422);

                stop();
            }
        }

        return $next($request, $response);
    }

}
