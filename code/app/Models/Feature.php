<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Subscription\MembershipPlan;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Feature
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|MembershipPlan[] $membershipPlans
 * @property-read int|null $membership_plans_count
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Feature newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Feature newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Feature extends BaseModelAbstract
{
    /**
     * @return BelongsToMany
     */
    public function membershipPlans(): BelongsToMany
    {
        return $this->belongsToMany(MembershipPlan::class);
    }
}
