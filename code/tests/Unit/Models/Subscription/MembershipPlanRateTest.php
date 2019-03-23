<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Subscription;

use App\Models\Subscription\MembershipPlanRate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * Class MembershipPlanRateTest
 * @package Tests\Unit\Models\Subscription
 */
class MembershipPlanRateTest extends TestCase
{
    public function testMembershipPlans()
    {
        $model = new MembershipPlanRate();
        $relation = $model->membershipPlan();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('membership_plans.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('membership_plan_rates.membership_plan_id', $relation->getQualifiedForeignKeyName());
    }
}