<?php
declare(strict_types=1);

namespace Tests\Integration\Policies\User;

use App\Models\User\Thread;
use App\Models\User\User;
use App\Policies\User\ThreadPolicy;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class ThreadPolicyTest
 * @package Tests\Integration\Policies\User
 */
class ThreadPolicyTest extends TestCase
{
    use DatabaseSetupTrait;

    public function testAllBlocks()
    {
        $loggedInUser = factory(User::class)->create();
        $requestedUser = factory(User::class)->create();

        $policy = new ThreadPolicy();

        $this->assertFalse($policy->all($loggedInUser, $requestedUser));
    }

    public function testAllPasses()
    {
        $user = factory(User::class)->create();

        $policy = new ThreadPolicy();

        $this->assertTrue($policy->all($user, $user));
    }

    public function testCreateBlocksUserLoggedInUserNotRequestedUser()
    {
        $loggedInUser = factory(User::class)->create();
        $requestedUser = factory(User::class)->create();

        $policy = new ThreadPolicy();

        $this->assertFalse($policy->create($loggedInUser, $requestedUser, []));
    }

    public function testCreateBlocksUserAddingExistingThread()
    {
        $policy = new ThreadPolicy();

        $user = factory(User::class)->create();

        $otherUsers = factory(User::class, 2)->create();

        /** @var Thread $thread */
        $thread = factory(Thread::class)->create();

        $thread->users()->sync([$user->id, $otherUsers[0]->id]);

        $this->assertFalse($policy->create($user, $user, [$otherUsers[0]->id]));

        $thread = factory(Thread::class)->create();

        $thread->users()->sync($otherUsers->pluck('id')->push($user->id));

        $this->assertFalse($policy->create($user, $user, [$otherUsers[0]->id, $otherUsers[1]->id]));
    }

    public function testCreatePasses()
    {
        $policy = new ThreadPolicy();

        $user = factory(User::class)->create();

        $otherUsers = factory(User::class, 2)->create();

        $this->assertTrue($policy->create($user, $user, [$otherUsers[0]->id]));

        /** @var Thread $thread */
        $thread = factory(Thread::class)->create();

        $thread->users()->sync([$user->id, $otherUsers[0]->id]);

        $this->assertTrue($policy->create($user, $user, [$otherUsers[0]->id, $otherUsers[1]->id]));
    }
}