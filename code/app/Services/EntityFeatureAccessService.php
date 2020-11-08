<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Models\IsAnEntity;
use App\Contracts\Repositories\Subscription\MembershipPlanRepositoryContract;
use App\Contracts\Services\EntityFeatureAccessServiceContract;

/**
 * Class EntityFeatureAccessService
 * @package App\Services
 */
class EntityFeatureAccessService implements EntityFeatureAccessServiceContract
{
    /**
     * @var MembershipPlanRepositoryContract
     */
    private MembershipPlanRepositoryContract $membershipPlanRepository;

    /**
     * EntityFeatureAccessService constructor.
     * @param MembershipPlanRepositoryContract $membershipPlanRepository
     */
    public function __construct(MembershipPlanRepositoryContract $membershipPlanRepository)
    {
        $this->membershipPlanRepository = $membershipPlanRepository;
    }

    /**
     * Tells us whether or not the passed in entity can acess the related feature ID
     *
     * @param IsAnEntity $entity
     * @param int $featureId
     * @return bool
     */
    public function canAccess(IsAnEntity $entity, int $featureId): bool
    {
        return false;
    }
}
