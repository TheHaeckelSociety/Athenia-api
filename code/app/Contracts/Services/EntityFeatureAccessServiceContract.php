<?php
declare(strict_types=1);

namespace App\Contracts\Services;

use App\Contracts\Models\IsAnEntity;

/**
 * Interface EntityFeatureAccessServiceContract
 * @package App\Contracts\Services
 */
interface EntityFeatureAccessServiceContract
{
    /**
     * Tells us whether or not the passed in entity can acess the related feature ID
     *
     * @param IsAnEntity $entity
     * @param int $featureId
     * @return bool
     */
    public function canAccess(IsAnEntity $entity, int $featureId): bool;
}
