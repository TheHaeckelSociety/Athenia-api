<?php
declare(strict_types=1);

namespace App\Repositories\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionRepositoryContract;
use App\Models\Subscription\Subscription;
use App\Repositories\BaseRepositoryAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class SubscriptionRepository
 * @package App\Repositories\Subscription
 */
class SubscriptionRepository extends BaseRepositoryAbstract implements SubscriptionRepositoryContract
{
    /**
     * SubscriptionRepository constructor.
     * @param Subscription $model
     * @param LogContract $log
     */
    public function __construct(Subscription $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }

    /**
     * Finds all subscriptions that expire at a certain date
     *
     * @param Carbon $expiresAt
     * @return Collection
     */
    public function findExpiring(Carbon $expiresAt): Collection
    {
        return $this->model->newQuery()
            ->where('expires_at', '>=', Carbon::instance($expiresAt)->setTime(0,0,0))
            ->where('expires_at', '<=', Carbon::instance($expiresAt)->setTime(23,59,59))
            ->get();
    }
}