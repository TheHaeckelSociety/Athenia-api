<?php
declare(strict_types=1);

namespace Tests\Integration\Policies\Vote;

use App\Models\User\User;
use App\Models\Vote\Ballot;
use App\Policies\Vote\BallotCompletionPolicy;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class BallotCompletionPolicyTest
 * @package Tests\Integration\Policies\Vote
 */
class BallotCompletionPolicyTest extends TestCase
{
    use DatabaseSetupTrait;

    public function testAll()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $policy = new BallotCompletionPolicy();

        $this->assertFalse($policy->all($user1, $user2));
        $this->assertTrue($policy->all($user1, $user1));
    }

    public function testCreate()
    {
        $policy = new BallotCompletionPolicy();

        $this->assertTrue($policy->create(new User(), new Ballot()));
    }
}
