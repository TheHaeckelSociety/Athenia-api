<?php
declare(strict_types=1);

namespace App\Contracts\Repositories\Subscription;

use App\Contracts\Repositories\BaseRepositoryContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface SubscriptionRepositoryContract
 * @package App\Contracts\Repositories\Subscription
 */
interface SubscriptionRepositoryContract extends BaseRepositoryContract
{
    /**
     * Finds all subscriptions that expire at a certain date
     *
     * @param Carbon $expiresAt
     * @return Collection
     */
    public function findExpiring(Carbon $expiresAt) : Collection;
}