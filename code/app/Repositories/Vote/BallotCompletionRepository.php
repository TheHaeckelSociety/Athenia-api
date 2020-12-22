<?php
declare(strict_types=1);

namespace App\Repositories\Vote;

use App\Contracts\Repositories\Vote\BallotCompletionRepositoryContract;
use App\Contracts\Repositories\Vote\VoteRepositoryContract;
use App\Models\BaseModelAbstract;
use App\Models\Vote\BallotCompletion;
use App\Repositories\BaseRepositoryAbstract;
use App\Repositories\Traits\NotImplemented;
use App\Traits\CanGetAndUnset;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class BallotCompletionRepository
 * @package App\Repositories\Vote
 */
class BallotCompletionRepository extends BaseRepositoryAbstract implements BallotCompletionRepositoryContract
{
    use NotImplemented\Update, CanGetAndUnset;

    /**
     * @var VoteRepositoryContract
     */
    private VoteRepositoryContract $voteRepository;

    /**
     * BallotCompletionRepository constructor.
     * @param BallotCompletion $model
     * @param LogContract $log
     * @param VoteRepositoryContract $voteRepository
     */
    public function __construct(BallotCompletion $model, LogContract $log,
                                VoteRepositoryContract $voteRepository)
    {
        parent::__construct($model, $log);
        $this->voteRepository = $voteRepository;
    }

    /**
     * overrides parent to sync votes
     *
     * @param array $data
     * @param BaseModelAbstract|null $relatedModel
     * @param array $forcedValues
     * @return BaseModelAbstract
     */
    public function create(array $data = [], BaseModelAbstract $relatedModel = null, array $forcedValues = [])
    {
        $votes = $this->getAndUnset($data, 'votes', []);

        $model = parent::create($data, $relatedModel, $forcedValues);

        $this->syncChildModels($this->voteRepository, $model, $votes);

        return $model;
    }
}
