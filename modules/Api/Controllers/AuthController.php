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

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Exceptions\AuthException;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Quantum\Di\Di;

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
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Request $request
     *  @param Response $response
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
     *  Gets the logged-in user data
     *  @OA\Get(
     *     path="/api/me",
     *     tags={"User"},
     *     summary="Gets the logged-in user data",
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
     *  @param Response $response
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Response $response
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Request $request
     *  @param Response $response
     */
    public function signup(Request $request, Response $response)
    {
        $user = auth()->signup($request->all());
        $fs = Di::get(FileSystem::class);
        $fs->makeDirectory(uploads_dir() . DS . $user->uuid);
        $response->json([
            'status' => self::STATUS_SUCCESS,
            'message' => t('common.successfully_signed_up')
        ]);
    }

    /**
     *  Activate action
     *  @OA\Get(
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Request $request
     *  @param Response $response
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
     *      response=400,
     *      description="Bad Request"
     *    ),
     *    @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Request $request
     *  @param Response $response
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
     *  @param Request $request
     *  @param Response $response
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
     *          example={
     *              "otp": "123456",
     *              "code": "otp_token"
     *              }
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
     *  @param Request $request
     *  @param Response $response
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
     *      response=401,
     *      description="Unauthenticated"
     *    )
     *  )
     *  @param Response $response
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
