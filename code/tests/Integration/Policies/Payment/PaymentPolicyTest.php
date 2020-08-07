<?php
declare(strict_types=1);

namespace Tests\Integration\Policies\Payment;

use App\Models\User\User;
use App\Policies\Payment\PaymentPolicy;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class SubscriptionPolicyTest
 * @package Tests\Integration\Policies\Payment
 */
class PaymentPolicyTest extends TestCase
{
    use DatabaseSetupTrait;

    public function testAll()
    {
        $policy = new PaymentPolicy();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->assertFalse($policy->all($user1, $user2));
        $this->assertTrue($policy->all($user1, $user1));
    }
}