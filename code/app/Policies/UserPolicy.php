<?php
/**
 * User policy
 */
declare(strict_types=1);

namespace App\Policies;

use App\Contracts\Policies\BasePolicyContract;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 * @package App\Policies
 */
class UserPolicy extends BasePolicyAbstract implements BasePolicyContract
{
    use HandlesAuthorization;

    /**
     * @var string can we view our self?
     */
    const ACTION_VIEW_SELF = 'view-self';

    /**
     * Determine if the user can view itself
     *
     * @param User $user
     * @param User $requested
     * @return bool
     */
    public function viewSelf(User $user, User $requested)
    {
        return $user->id == $requested->id;
    }
}