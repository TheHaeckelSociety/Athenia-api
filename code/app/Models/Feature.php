<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Subscription\MembershipPlan;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Feature
 * @package App\Models
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
