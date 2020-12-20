<?php
declare(strict_types=1);

namespace App\Repositories\Vote;

use App\Contracts\Repositories\Vote\BallotSubjectRepositoryContract;
use App\Models\Vote\BallotItem;
use App\Repositories\BaseRepositoryAbstract;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class BallotSubjectRepository
 * @package App\Repositories\Vote
 */
class BallotSubjectRepository extends BaseRepositoryAbstract implements BallotSubjectRepositoryContract
{
    /**
     * BallotSubjectRepository constructor.
     * @param BallotItem $model
     * @param LogContract $log
     */
    public function __construct(BallotItem $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }
}
