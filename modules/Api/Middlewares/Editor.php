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

namespace Modules\Api\Middlewares;

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class Editor
 * @package Modules\Api\Middlewares
 */
class Editor extends QtMiddleware
{

    /**
     * @var \Quantum\Libraries\Validation\Validator
     */
    private $validator;

    /**
     * @var string[]
     */
    const ROLES = ['admin', 'editor'];

    /**
     * Class constructor
     * @param \Quantum\Http\Request $request
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
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     */
    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (!in_array(auth()->user()->getFieldValue('role'), self::ROLES)) {
            $response->json([
                'status' => 'error',
                'message' => t('validation.unauthorizedRequest')
            ]);

            stop();
        }

        if ($request->isMethod('post') || $request->isMethod('put')) {
            if (!$this->validator->isValid($request->all())) {
                $response->json([
                    'status' => 'error',
                    'message' => $this->validator->getErrors()
                ]);

                stop();
            }
        }

        return $next($request, $response);
    }

}
