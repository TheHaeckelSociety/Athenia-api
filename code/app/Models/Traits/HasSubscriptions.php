<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasSubscriptions
 * @package App\Models\Traits
 */
trait HasSubscriptions
{
    /**
     * All Subscriptions this subscriber has signed up to
     *
     * @return MorphMany
     */
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'subscriber');
    }
}