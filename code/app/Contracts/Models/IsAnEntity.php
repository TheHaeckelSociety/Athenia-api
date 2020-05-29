<?php
declare(strict_types=1);

namespace App\Contracts\Models;

use App\Models\User\User;

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
     * @param int|null $role An optional role id that we are checking for
     * @return bool
     */
    public function canUserManageEntity(User $user, int $role = null): bool;
}