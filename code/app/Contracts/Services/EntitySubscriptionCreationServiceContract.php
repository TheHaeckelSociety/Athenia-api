<?php
declare(strict_types=1);

namespace App\Contracts\Services;

use App\Contracts\Models\IsAnEntity;
use App\Models\Subscription\Subscription;

/**
 * Interface EntitySubscriptionCreationService
 * @package App\Contracts\Services
 */
interface EntitySubscriptionCreationServiceContract
{
    /**
     * Creates a subscription for an entity while replacing any current one that may exist for an entity
     *
     * @param IsAnEntity $entity
     * @param array $data
     * @return Subscription
     */
    public function createSubscription(IsAnEntity $entity, array $data): Subscription;
}
