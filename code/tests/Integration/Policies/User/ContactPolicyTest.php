<?php
declare(strict_types=1);

namespace Tests\Integration\Policies\User;

use App\Models\User\Contact;
use App\Models\User\User;
use App\Policies\User\ContactPolicy;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class ContactPolicyTest
 * @package Tests\Integration\Policies\User
 */
class ContactPolicyTest extends TestCase
{
    use DatabaseSetupTrait;

    public function testAllPasses()
    {
        $user = User::factory()->create();

        $policy = new ContactPolicy();

        $this->assertTrue($policy->all($user, $user));
    }

    public function testAllFails()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $policy = new ContactPolicy();

        $this->assertFalse($policy->all($user1, $user2));
    }

    public function testCreatePasses()
    {
        $user = User::factory()->create();

        $policy = new ContactPolicy();

        $this->assertTrue($policy->create($user, $user));
    }

    public function testCreateFails()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $policy = new ContactPolicy();

        $this->assertFalse($policy->create($user1, $user2));
    }

    public function testUpdatePasses()
    {
        $user = User::factory()->create();

        $policy = new ContactPolicy();

        $initiatedContact = Contact::factory()->create([
            'initiated_by_id' => $user->id,
        ]);
        $this->assertTrue($policy->update($user, $user, $initiatedContact));

        $requestedContact = Contact::factory()->create([
            'requested_id' => $user->id,
        ]);
        $this->assertTrue($policy->update($user, $user, $requestedContact));
    }

    public function testUpdateFails()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $contact = Contact::factory()->create([
            'initiated_by_id' => $user2->id,
        ]);

        $policy = new ContactPolicy();

        $this->assertFalse($policy->update($user1, $user2, $contact));
        $this->assertFalse($policy->update($user1, $user1, $contact));
    }

    public function testDeletePasses()
    {
        $user = User::factory()->create();

        $policy = new ContactPolicy();

        $initiatedContact = Contact::factory()->create([
            'initiated_by_id' => $user->id,
        ]);
        $this->assertTrue($policy->update($user, $user, $initiatedContact));

        $requestedContact = Contact::factory()->create([
            'requested_id' => $user->id,
        ]);
        $this->assertTrue($policy->delete($user, $user, $requestedContact));
    }

    public function testDeleteFails()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $contact = Contact::factory()->create([
            'initiated_by_id' => $user2->id,
        ]);

        $policy = new ContactPolicy();

        $this->assertFalse($policy->delete($user1, $user2, $contact));
        $this->assertFalse($policy->delete($user1, $user1, $contact));
    }
}
