<?php
declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User\User;
use App\Policies\BasePolicyAbstract;

/**
 * Class ThreadPolicy
 * @package App\Policies\User
 */
class ThreadPolicy extends BasePolicyAbstract
{
    /**
     * A user can see all of their threads
     *
     * @param User $loggedInUser
     * @param User $requestedUser
     * @return bool
     */
    public function all(User $loggedInUser, User $requestedUser)
    {
        return $loggedInUser->id == $requestedUser->id;
    }

    /**
     * Users can create threads only for themselves, and with users that they do not already have a thread with
     *
     * @param User $loggedInUser
     * @param User $requestedUser
     * @param array $userIds
     * @return bool
     */
    public function create(User $loggedInUser, User $requestedUser, array $userIds)
    {
        if ($loggedInUser->id != $requestedUser->id) {
            return false;
        }

        $userCollection = collect($userIds);

        foreach ($requestedUser->threads as $thread) {
            if ($thread->users->some(function (User $user) use ($userCollection){
                return $userCollection->contains($user->id);
            })) {
                return false;
            }
        }

        return true;
    }
}