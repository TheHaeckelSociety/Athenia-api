<?php
declare(strict_types=1);

namespace App\Policies\Payment;

use App\Contracts\Models\IsAnEntity;
use App\Models\User\User;
use App\Policies\BasePolicyAbstract;

/**
 * Class PaymentMethodPolicy
 * @package App\Policies\Payment
 */
class PaymentPolicy extends BasePolicyAbstract
{
    /**
     * Only available for super admins and people related to the entity
     *
     * @param User $loggedInUser
     * @param IsAnEntity $entity
     * @return bool
     */
    public function all(User $loggedInUser, IsAnEntity $entity)
    {
        return $entity->canUserManageEntity($loggedInUser);
    }
}