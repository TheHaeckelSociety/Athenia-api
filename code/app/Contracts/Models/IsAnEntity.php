<?php
declare(strict_types=1);

namespace App\Contracts\Models;

use App\Models\Role;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface CanHaveMultipleOwnerTypes
 * @package App\Contracts\Models
 * @property int $id
 */
interface IsAnEntity extends CanBeMorphedTo
{
    /**
     * Tells us whether or not the logged in user can manage this entity
     *
     * @param User $user The logged in user
     * @param int $role An optional role id that we are checking for
     * @return bool
     */
    public function canUserManageEntity(User $user, int $role = Role::MANAGER): bool;

    /**
     * This is for the relation for the payment methods
     *
     * @return MorphMany
     */
    public function paymentMethods(): MorphMany;

    /**
     * All payments this entity has made
     *
     * @return MorphMany
     */
    public function payments(): MorphMany;
}