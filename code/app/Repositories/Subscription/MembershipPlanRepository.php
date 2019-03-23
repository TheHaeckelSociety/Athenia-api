<?php
declare(strict_types=1);

namespace App\Repositories\Subscription;

use App\Contracts\Repositories\Subscription\MembershipPlanRepositoryContract;
use App\Models\Subscription\MembershipPlan;
use App\Repositories\BaseRepositoryAbstract;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class MembershipPlanRepository
 * @package App\Repositories\Subscription
 */
class MembershipPlanRepository extends BaseRepositoryAbstract implements MembershipPlanRepositoryContract
{
    /**
     * MembershipPlanRepository constructor.
     * @param MembershipPlan $model
     * @param LogContract $log
     */
    public function __construct(MembershipPlan $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }
}