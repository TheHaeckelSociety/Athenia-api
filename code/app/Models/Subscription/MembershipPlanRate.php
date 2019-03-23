<?php
declare(strict_types=1);

namespace App\Models\Subscription;

use App\Models\BaseModelAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MembershipPlanRate
 *
 * @property int $id
 * @property int $membership_plan_id
 * @property float $cost
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \App\Models\Subscription\MembershipPlan $membershipPlan
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereMembershipPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlanRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MembershipPlanRate extends BaseModelAbstract
{
    /**
     * The membership plan this is related to
     *
     * @return BelongsTo
     */
    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }
}