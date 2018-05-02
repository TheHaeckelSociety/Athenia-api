<?php
declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User\User;
use App\Policies\UserPolicy;
use Tests\TestCase;

/**
 * Class UserPolicyTest
 * @package Tests\Unit\Policies
 */
class UserPolicyTest extends TestCase
{
    public function testBefore()
    {
        $policy = new UserPolicy();

        $this->assertNull($policy->before(new User()));
    }

    public function testAll()
    {
        $policy = new UserPolicy();

        $this->assertFalse($policy->all(new User()));
    }

    public function testView()
    {
        $policy = new UserPolicy();

        $this->assertFalse($policy->view(new User(), new User()));
    }

    public function testCreate()
    {
        $policy = new UserPolicy();

        $this->assertFalse($policy->create(new User()));
    }

    public function testUpdate()
    {
        $policy = new UserPolicy();

        $this->assertFalse($policy->update(new User(), new User()));
    }

    public function testDelete()
    {
        $policy = new UserPolicy();

        $this->assertFalse($policy->delete(new User(), new User()));
    }

    public function testViewSelfPasses()
    {
        $policy = new UserPolicy();

        $loggedInUser = new User(['id' => 5]);

        $this->assertTrue($policy->viewSelf($loggedInUser, $loggedInUser));
    }

    public function testViewSelfFails()
    {
        $policy = new UserPolicy();

        $loggedInUser = new User(['id' => 5]);
        $requestedUser = new User(['id' => 9420]);

        $this->assertTrue($policy->viewSelf($loggedInUser, $requestedUser));
    }
}