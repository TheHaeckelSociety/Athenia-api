<?php
declare(strict_types=1);

namespace App\Http\V1\Controllers;

use App\Contracts\Repositories\User\UserRepositoryContract;
use App\Http\V1\Controllers\Traits\HasViewRequests;
use App\Http\V1\Requests;
use App\Models\User\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 * @package App\Http\V1\Controllers\User
 */
class UserController extends BaseControllerAbstract
{
    use HasViewRequests;

    /**
     * @var UserRepositoryContract
     */
    protected $userRepository;

    /**
     * @var Hasher
     */
    protected $hasher;

    /**
     * UsersController constructor.
     * @param UserRepositoryContract $userRepository
     * @param Hasher $hasher
     */
    public function __construct(UserRepositoryContract $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @SWG\Post(
     *     path="/sign-up",
     *     summary="Signs Up a new user",
     *     tags={"Users"},
     *     @SWG\Parameter(ref="#/parameters/AuthorizationHeader"),
     *     @SWG\Parameter(
     *          name="incoming-model",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/User"),
     *          description="The model to create"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Model created successfully",
     *          @SWG\Schema(ref="#/definitions/User"),
     *          @SWG\Header(
     *              header="Location",
     *              description="The URL to retrieve the newly created item",
     *              type="string",
     *              format="url"
     *          ),
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
     * @param Requests\User\SignUpRequest $request
     * @return JsonResponse
     */
    public function signUp(Requests\User\SignUpRequest $request)
    {
        $data = $request->json()->all();

        $forcedData = [
            'password' => $this->hasher->make($data['password']),
        ];

        $model = $this->userRepository->create($data, null, $forcedData);

        return new JsonResponse($model, 201);
    }

    /**
     * Display the specified resource's self
     *
     * @SWG\Get(
     *     path="/users/me",
     *     summary="Show currently logged in user info",
     *     tags={"Users"},
     *     @SWG\Parameter(ref="#/parameters/AuthorizationHeader"),
     *     @SWG\Response(
     *          response=200,
     *          description="Returns a single model",
     *          @SWG\Schema(ref="#/definitions/User"),
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
     *          response=404,
     *          ref="#/responses/Standard404ItemNotFoundResponse"
     *      ),
     *     @SWG\Response(
     *          response="default",
     *          ref="#/responses/Standard500ErrorResponse"
     *      ),
     * )
     *
     * @param Requests\User\MeRequest $request
     * @return JsonResponse
     */
    public function me(Requests\User\MeRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        return new JsonResponse($user->load($this->expand($request)));
    }
}