<?php
declare(strict_types=1);

namespace App\Repositories\Vote;

use App\Contracts\Repositories\Vote\BallotItemOptionRepositoryContract;
use App\Models\Vote\BallotItemOption;
use App\Repositories\BaseRepositoryAbstract;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class BallotItemOptionRepository
 * @package App\Repositories\Vote
 */
class BallotItemOptionRepository extends BaseRepositoryAbstract implements BallotItemOptionRepositoryContract
{
    /**
     * BallotItemOptionRepository constructor.
     * @param BallotItemOption $model
     * @param LogContract $log
     */
    public function __construct(BallotItemOption $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }
}
