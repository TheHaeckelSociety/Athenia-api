<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Subscription;

use App\Models\Subscription\MembershipPlan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * Class MembershipPlanTest
 * @package Tests\Unit\Models\Subscription
 */
class MembershipPlanTest extends TestCase
{
    public function testSubscriptions()
    {
        $user = new MembershipPlan();
        $relation = $user->subscriptions();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('membership_plans.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('subscriptions.membership_plan_id', $relation->getQualifiedForeignKeyName());
    }
}