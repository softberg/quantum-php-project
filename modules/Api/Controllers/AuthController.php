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

namespace Modules\Api\Controllers;

use Quantum\Exceptions\AuthException;
use Quantum\Http\Response;
use Quantum\Http\Request;

/**
 * Class AuthController
 * @package Modules\Api\Controllers
 */
class AuthController extends ApiController
{

    /**
     * Status error
     */
    const STATUS_ERROR = 'error';

    /**
     * Status success
     */
    const STATUS_SUCCESS = 'success';

    /**
     *  Sign in action
     *  @OA\Post(
     *    path="/api/signin",
     *    tags={"SignIn & SignOut"},
     *    summary="Login Api",
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
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Request $request
     *  @param \Quantum\Http\Response $response
     */
    public function signin(Request $request, Response $response)
    {
        try {
            $code = auth()->signin($request->get('email'), $request->get('password'));

            if (filter_var(config()->get('2FA'), FILTER_VALIDATE_BOOLEAN)) {
                $response->set('code', $code);
            }

            $response->json([
                'status' => self::STATUS_SUCCESS
            ]);
        } catch (AuthException $e) {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     *  Gets the logged in user data
     *  @OA\Get(
     *     path="/api/me",
     *     tags={"User"},
     *     summary="Return auth user",
     *     operationId="me",
     *     security={
     *       {"bearer_token": {}
     *     }},
     *    @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *        mediaType="application/json",
     *      )
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Response $response
     */
    public function me(Response $response)
    {
        $response->json([
            'status' => self::STATUS_SUCCESS,
            'data' => [
                'firstname' => auth()->user()->firstname,
                'lastname' => auth()->user()->lastname,
                'email' => auth()->user()->email
            ]
        ]);
    }

    /**
     *  Sign out action
     *  @OA\Get(
     *    path="/api/signout",
     *    tags={"SignIn & SignOut"},
     *    summary="Signout",
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Response $response
     */
    public function signout(Response $response)
    {
        if (auth()->signout()) {
            $response->json([
                'status' => self::STATUS_SUCCESS
            ]);
        } else {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => t('validation.unauthorizedRequest')
            ]);
        }
    }

    /**
     *  Sign up action
     *  @OA\Post(
     *  path="/api/signup",
     *    tags={"SignIn & SignOut"},
     *    summary="SignUp Api",
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Request $request
     *  @param \Quantum\Http\Response $response
     */
    public function signup(Request $request, Response $response)
    {
        if (auth()->signup($request->all())) {
            $response->json([
                'status' => self::STATUS_SUCCESS,
                'message' => t('common.successfully_signed_up')
            ]);
        }
    }

    /**
     *  Activate action
     *  @OA\Get(
     *  path="/api/activate/{activate_token}",
     *    tags={"SignIn & SignOut"},
     *    summary="Activate profile",
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Request $request
     *  @param \Quantum\Http\Response $response
     */
    public function activate(Request $request, Response $response)
    {
        auth()->activate($request->get('activation_token'));

        $response->json([
            'status' => self::STATUS_SUCCESS,
            'message' => t('common.account_activated')
        ]);
    }

    /**
     *  Forget action
     *  @OA\Post(
     *  path="/api/forget",
     *    tags={"SignIn & SignOut"},
     *    summary="Forget password Api",
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
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Request $request
     *  @param \Quantum\Http\Response $response
     */
    public function forget(Request $request, Response $response)
    {
        auth()->forget($request->get('email'));

        $response->json([
            'status' => self::STATUS_SUCCESS,
            'message' => t('common.check_email')
        ]);
    }

    /**
     *  Reset action
     *  @OA\Post(
     *  path="/api/reset/{reset_token}",
     *    tags={"SignIn & SignOut"},
     *    summary="Reset password",
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
     *          example={"password": "password","repeat_password": "password"}
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Request $request
     *  @param \Quantum\Http\Response $response
     */
    public function reset(Request $request, Response $response)
    {
        auth()->reset($request->get('reset_token'), $request->get('password'));

        $response->json([
            'status' => self::STATUS_SUCCESS
        ]);
    }

    /**
     *  Verify action
     *  @OA\Post(
     *  path="/api/verify",
     *    tags={"SignIn & SignOut"},
     *    summary="Verify account",
     *    operationId="accountVerify",
     *    @OA\RequestBody(
     *      @OA\MediaType(
     *      mediaType="application/json",
     *        @OA\Schema(
     *          @OA\Property(
     *            property="otp_code",
     *            type="string"
     *          ),
     *          example={"otp_code": "123456"}
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Request $request
     *  @param \Quantum\Http\Response $response
     */
    public function verify(Request $request, Response $response)
    {
        try {
            auth()->verifyOtp((int) $request->get('otp'), $request->get('code'));

            $response->json([
                'status' => self::STATUS_SUCCESS
            ]);
        } catch (AuthException $e) {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     *  Resend action
     *  @OA\Get(
     *  path="/api/resend/{otp_token}",
     *    tags={"SignIn & SignOut"},
     *    summary="Resend OTP code",
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param \Quantum\Http\Response $response
     */
    public function resend(Response $response)
    {
        try {
            $response->json([
                'status' => self::STATUS_SUCCESS,
                'code' => auth()->resendOtp(route_param('code'))
            ]);
        } catch (AuthException $e) {
            $response->json([
                'status' => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
}
