<?php
declare(strict_types=1);

namespace App\Http\Core\Controllers\Ballot;

use App\Contracts\Repositories\Vote\BallotCompletionRepositoryContract;
use App\Http\Core\Controllers\BaseControllerAbstract;
use App\Http\Core\Requests;
use App\Models\Vote\Ballot;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class BallotCompletionControllerAbstract
 * @package App\Http\Core\Controllers\Ballot
 */
abstract class BallotCompletionControllerAbstract extends BaseControllerAbstract
{
    /**
     * @var BallotCompletionRepositoryContract
     */
    private BallotCompletionRepositoryContract $repository;

    /**
     * BallotCompletionControllerAbstract constructor.
     * @param BallotCompletionRepositoryContract $repository
     */
    public function __construct(BallotCompletionRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Requests\Ballot\BallotCompletion\StoreRequest $request
     * @param Ballot $ballot
     * @return JsonResponse
     */
    public function store(Requests\Ballot\BallotCompletion\StoreRequest $request, Ballot $ballot)
    {
        $data = $request->json()->all();

        $data['user_id'] = Auth::user()->id;

        return new JsonResponse($this->repository->create($data, $ballot), 201);
    }
}
