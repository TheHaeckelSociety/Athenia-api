<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Subscription;

use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * Class SubscriptionTest
 * @package Tests\Unit\Models\Subscription
 */
class SubscriptionTest extends TestCase
{
    public function testMembershipPlanRate()
    {
        $model = new Subscription();
        $relation = $model->membershipPlanRate();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('membership_plan_rates.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('subscriptions.membership_plan_rate_id', $relation->getQualifiedForeignKeyName());
    }

    public function testPaymentMethod()
    {
        $model = new Subscription();
        $relation = $model->paymentMethod();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('payment_methods.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('subscriptions.payment_method_id', $relation->getQualifiedForeignKeyName());
    }

    public function testUser()
    {
        $model = new Subscription();
        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('subscriptions.user_id', $relation->getQualifiedForeignKeyName());
    }
}