<?php
declare(strict_types=1);

namespace Tests\Integration\Policies\User;

use App\Models\User\Message;
use App\Models\User\Thread;
use App\Models\User\User;
use App\Policies\User\MessagePolicy;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class MessagePolicyTest
 * @package Tests\Integration\Policies\User
 */
class MessagePolicyTest extends TestCase
{
    use DatabaseSetupTrait;

    public function testAllBlocksUserMismatch()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $loggedInUser = factory(User::class)->create();
        $requestedUser = factory(User::class)->create();

        $this->assertFalse($policy->all($loggedInUser, $requestedUser, $thread));
    }

    public function testAllBlocksNotInThread()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();

        $this->assertFalse($policy->all($user, $user, $thread));
    }

    public function testAllPasses()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();

        $thread->users()->sync([$user->id]);

        $this->assertTrue($policy->all($user, $user, $thread));
    }

    public function testCreateBlocksUserMismatch()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $loggedInUser = factory(User::class)->create();
        $requestedUser = factory(User::class)->create();

        $this->assertFalse($policy->create($loggedInUser, $requestedUser, $thread));
    }

    public function testCreateBlocksNotInThread()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();

        $this->assertFalse($policy->create($user, $user, $thread));
    }

    public function testCreatePasses()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();

        $thread->users()->sync([$user->id]);

        $this->assertTrue($policy->create($user, $user, $thread));
    }

    public function testUpdateBlocksUserMismatch()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $loggedInUser = factory(User::class)->create();
        $requestedUser = factory(User::class)->create();
        $message = factory(Message::class)->create();

        $this->assertFalse($policy->update($loggedInUser, $requestedUser, $thread, $message));
    }

    public function testUpdateBlocksUserNotInThread()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();
        $message = factory(Message::class)->create();

        $this->assertFalse($policy->update($user, $user, $thread, $message));
    }

    public function testUpdateBlocksMessageNotInThread()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();
        $message = factory(Message::class)->create();

        $thread->users()->sync([$user->id]);

        $this->assertFalse($policy->update($user, $user, $thread, $message));
    }

    public function testUpdateBlocksUserNotSentMessage()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();
        $message = factory(Message::class)->create([
            'thread_id' => $thread->id,
            'to_id' => factory(User::class)->create()->id,
        ]);

        $thread->users()->sync([$user->id]);

        $this->assertFalse($policy->update($user, $user, $thread, $message));
    }

    public function testUpdatePasses()
    {
        $policy = new MessagePolicy();

        $thread = factory(Thread::class)->create();
        $user = factory(User::class)->create();
        $message = factory(Message::class)->create([
            'thread_id' => $thread->id,
            'to_id' => $user->id,
        ]);

        $thread->users()->sync([$user->id]);

        $this->assertTrue($policy->update($user, $user, $thread, $message));
    }
}