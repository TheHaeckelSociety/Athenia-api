<?php
declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User\Message;
use App\Models\User\Thread;
use App\Models\User\User;
use App\Policies\BasePolicyAbstract;

/**
 * Class MessagePolicy
 * @package App\Policies\User
 */
class MessagePolicy extends BasePolicyAbstract
{
    /**
     * A user can see all of their threads
     *
     * @param User $loggedInUser
     * @param User $requestedUser
     * @param Thread $thread
     * @return bool
     */
    public function all(User $loggedInUser, User $requestedUser, Thread $thread)
    {
        return $loggedInUser->id == $requestedUser->id && $thread->users->contains($requestedUser->id);
    }

    /**
     * Users can create message in threads they are apart of
     *
     * @param User $loggedInUser
     * @param User $requestedUser
     * @param Thread $thread
     * @return bool
     */
    public function create(User $loggedInUser, User $requestedUser, Thread $thread)
    {
        return $loggedInUser->id == $requestedUser->id && $thread->users->pluck('id')->contains($requestedUser->id);
    }

    /**
     * Only users that have been sent a message can update the message
     *
     * @param User $loggedInUser
     * @param User $requestedUser
     * @param Thread $thread
     * @param Message $message
     * @return bool
     */
    public function update(User $loggedInUser, User $requestedUser, Thread $thread, Message $message)
    {
        return $loggedInUser->id == $requestedUser->id &&
            $thread->users->pluck('id')->contains($requestedUser->id) &&
            $thread->id == $message->thread_id && $message->to_id == $loggedInUser->id;
    }
}