<?php
/**
 * Controller that contains all functions to handle API Authentication
 */
declare(strict_types=1);

namespace App\Http\V1\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\V1\Requests;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class AuthenticationController
 *
 * @SWG\Definition(
 *     definition="AuthenticationToken",
 *     type="object",
 *     @SWG\Property(
 *         property="token",
 *         type="string",
 *         description="The authentication token"
 *     )
 * )
 *
 * @package App\Http\V1\Controllers
 */
class AuthenticationController extends BaseControllerAbstract
{
    /**
     * @var JWTAuth
     */
    private $auth;

    /**
     * AuthenticationController constructor.
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Authenticate the user and get the token - or 401
     *
     * Should receive username and password for terminal or email and password for website.
     *
     * @SWG\Post(
     *     path="/auth/login",
     *     summary="Login to get JWT, should receive email and password.",
     *     consumes={"application/x-www-form-urlencoded"},
     *     tags={"Auth"},
     *     @SWG\Parameter(
     *          name="email",
     *          in="formData",
     *          description="The user's email",
     *          type="string",
     *          format="email"
     *     ),
     *     @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          description="The user's password",
     *          required=true,
     *          type="string",
     *          maxLength=255
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful authentication",
     *          @SWG\Schema(ref="#/definitions/AuthenticationToken"),
     *          @SWG\Header(
     *              header="X-RateLimit-Limit",
     *              description="The number of allowed requests in the period",
     *              type="integer"
     *          ),
     *          @SWG\Header(
     *              header="X-RateLimit-Remaining",
     *              description="The number of remaining requests in the period",
     *              type="integer"
     *          )
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          ref="#/responses/Standard400BadRequestResponse"
     *      ),
     *     @SWG\Response(
     *          response=401,
     *          ref="#/responses/Standard401UnauthorizedResponse"
     *      ),
     *     @SWG\Response(
     *          response="default",
     *          ref="#/responses/Standard500ErrorResponse"
     *      ),
     * )
     *
     * @param Requests\Authentication\LoginRequest $request
     * @return JsonResponse
     * @throws JWTException
     */
    public function login(Requests\Authentication\LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (! $token = $this->auth->attempt($credentials)) {
            throw new JWTException('Invalid login credentials.', 401);
        }

        return new JsonResponse([
            'token' => $token,
        ]);
    }

    /**
     * This handles refreshing an authentication token
     *
     * @SWG\Post(
     *     path="/auth/refresh",
     *     summary="Get a refreshed token",
     *     tags={"Auth"},
     *     @SWG\Parameter(ref="#/parameters/AuthorizationHeader"),
     *     @SWG\Response(
     *          response=200,
     *          description="New Refreshed token",
     *          @SWG\Schema(ref="#/definitions/AuthenticationToken"),
     *          @SWG\Header(
     *              header="X-RateLimit-Limit",
     *              description="The number of allowed requests in the period",
     *              type="integer"
     *          ),
     *          @SWG\Header(
     *              header="X-RateLimit-Remaining",
     *              description="The number of remaining requests in the period",
     *              type="integer"
     *          )
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          ref="#/responses/Standard400BadRequestResponse"
     *      ),
     *     @SWG\Response(
     *          response=401,
     *          ref="#/responses/Standard401UnauthorizedResponse"
     *      ),
     *     @SWG\Response(
     *          response="default",
     *          ref="#/responses/Standard500ErrorResponse"
     *      ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request)
    {
        $newToken = $this->auth->setRequest($request)->parseToken()->refresh();

        return new JsonResponse([
            'token' => $newToken,
        ]);
    }

    /**
     * Handles logging out if there is a valid JWT
     *
     * @SWG\Post(
     *     path="/auth/logout",
     *     summary="Log out",
     *     tags={"Auth"},
     *     @SWG\Parameter(ref="#/parameters/AuthorizationHeader"),
     *     @SWG\Response(
     *          response=200,
     *          description="The token has been destroyed",
     *          @SWG\Schema(ref="#/definitions/Status"),
     *          @SWG\Header(
     *              header="X-RateLimit-Limit",
     *              description="The number of allowed requests in the period",
     *              type="integer"
     *          ),
     *          @SWG\Header(
     *              header="X-RateLimit-Remaining",
     *              description="The number of remaining requests in the period",
     *              type="integer"
     *          )
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          ref="#/responses/Standard400BadRequestResponse"
     *      ),
     *     @SWG\Response(
     *          response=401,
     *          ref="#/responses/Standard401UnauthorizedResponse"
     *      ),
     *     @SWG\Response(
     *          response="default",
     *          ref="#/responses/Standard500ErrorResponse"
     *      ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $this->auth->invalidate($this->auth->getToken());

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }
}