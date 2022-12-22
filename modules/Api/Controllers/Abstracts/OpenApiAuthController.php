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

namespace Modules\Api\Controllers\Abstracts;

use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class OpenApiAuthController
 * @package Modules\Api
 */
abstract class OpenApiAuthController extends ApiController
{

    /**
     * Sign in action
     * @OA\Post(
     *    path="/api/signin",
     *    tags={"Authentication"},
     *    summary="Sign in action",
     *    operationId="userSignIn",
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *      mediaType="application/json",
     *        @OA\Schema(
     *          @OA\Property(
     *            property="email",
     *            type="string"
     *          ),
     *          @OA\Property(
     *            property="password",
     *            type="string"
     *          ),
     *          example={"email": "rgaylord@gmail.com", "password": "password"}
     *        )
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function signin(Request $request, Response $response);

    /**
     * Gets the logged-in user data
     * @OA\Get(
     *     path="/api/me",
     *     tags={"User"},
     *     summary="Gets the logged-in user data",
     *     operationId="me",
     *     security={
     *       {"bearer_token": {}}
     *     },
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     */
    abstract public function me(Response $response);

    /**
     * Sign out action
     * @OA\Get(
     *    path="/api/signout",
     *    tags={"Authentication"},
     *    summary="Sign out action",
     *    operationId="signout",
     *    @OA\Parameter(
     *      name="refresh_token",
     *      description="Refresh token",
     *      required=true,
     *      in="header",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthorized Request"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     */
    abstract public function signout(Response $response);

    /**
     * Sign up action
     * @OA\Post(
     *  path="/api/signup",
     *    tags={"Authentication"},
     *    summary="Sign up action",
     *    operationId="signUpApi",
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *        mediaType="application/json",
     *        @OA\Schema(
     *          @OA\Property(
     *            property="email",
     *            type="string",
     *          ),
     *          @OA\Property(
     *            property="password",
     *            type="string"
     *          ),
     *          @OA\Property(
     *            property="firstname",
     *            type="string",
     *          ),
     *          @OA\Property(
     *            property="lastname",
     *            type="string",
     *          ),
     *          example={"email": "mail@example.com", "password": "password",  "firstname": "Jon", "lastname": "Smit"}
     *        )
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function signup(Request $request, Response $response);

    /**
     * Activate action
     * @OA\Get(
     *  path="/api/activate/{activate_token}",
     *    tags={"Authentication"},
     *    summary="Activate action",
     *    operationId="activateProfile",
     *    @OA\Parameter(
     *      name="activate_token",
     *      description="Activate token",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function activate(Request $request, Response $response);

    /**
     * Forget action
     * @OA\Post(
     *  path="/api/forget",
     *    tags={"Authentication"},
     *    summary="Forget action",
     *    operationId="forgetPassword",
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *      mediaType="application/json",
     *        @OA\Schema(
     *          @OA\Property(
     *            property="username",
     *            type="string"
     *          ),
     *          example={"email": "mail@example.com"}
     *        )
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function forget(Request $request, Response $response);

    /**
     * Reset action
     * @OA\Post(
     *  path="/api/reset/{reset_token}",
     *    tags={"Authentication"},
     *    summary="Reset action",
     *    operationId="resetPassword",
     *    @OA\Parameter(
     *      name="reset_token",
     *      description="Reset token",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *        mediaType="application/json",
     *        @OA\Schema(
     *          @OA\Property(
     *            property="password",
     *            type="string"
     *          ),
     *          @OA\Property(
     *            property="repeat_password",
     *            type="string"
     *          ),
     *          example={"password": "password", "repeat_password": "password"}
     *        )
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function reset(Request $request, Response $response);

    /**
     * Verify action
     * @OA\Post(
     *  path="/api/verify",
     *    tags={"Authentication"},
     *    summary="Verify action",
     *    operationId="accountVerify",
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *      mediaType="application/json",
     *        @OA\Schema(
     *          @OA\Property(
     *            property="otp_code",
     *            type="string"
     *          ),
     *          example={"otp": "123456", "code": "otp_token"}
     *        )
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Request $request
     * @param Response $response
     */
    abstract public function verify(Request $request, Response $response);

    /**
     * Resend action
     * @OA\Get(
     *  path="/api/resend/{otp_token}",
     *    tags={"Authentication"},
     *    summary="Resend action",
     *    operationId="resendOTP",
     *    @OA\Parameter(
     *      name="otp_token",
     *      description="OTP token",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *        type="string"
     *      )
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error"
     *    )
     *  )
     * @param Response $response
     */
    abstract public function resend(Response $response);
}
