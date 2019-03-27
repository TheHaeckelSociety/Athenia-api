<?php
declare(strict_types=1);

namespace App\Models\Subscription;

use App\Models\BaseModelAbstract;
use App\Models\Payment\PaymentMethod;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Subscription
 *
 * @package App\Models\Subscription
 * @property int $id
 * @property int $membership_plan_rate_id
 * @property int $payment_method_id
 * @property int $user_id
 * @property string|null $last_renewed_at
 * @property string|null $subscribed_at
 * @property string|null $expires_at
 * @property string|null $canceled_at
 * @property bool $recurring
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \App\Models\Subscription\MembershipPlanRate $membershipPlanRate
 * @property-read \App\Models\Payment\PaymentMethod $paymentMethod
 * @property-read \App\Models\User\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereLastRenewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereMembershipPlanRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePaymentMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSubscribedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 * @mixin \Eloquent
 */
class Subscription extends BaseModelAbstract
{
    /**
     * The membership plan rate this subscription is signed up for
     *
     * @return BelongsTo
     */
    public function membershipPlanRate(): BelongsTo
    {
        return $this->belongsTo(MembershipPlanRate::class);
    }

    /**
     * The payment method that is used to renew this subscription
     *
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * The user this subscription is for
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}