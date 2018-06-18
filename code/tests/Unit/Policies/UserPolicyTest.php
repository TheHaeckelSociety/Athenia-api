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