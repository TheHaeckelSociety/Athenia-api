<?php
declare(strict_types=1);

namespace App\Models\Subscription;

use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentMethod;
use App\Models\Traits\HasValidationRules;
use App\Models\User\User;
use App\Validators\Subscription\MembershipPlanRateIsActiveValidator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

/**
 * Class Subscription
 *
 * @package App\Models\Subscription
 * @property int $id
 * @property int $membership_plan_rate_id
 * @property int $payment_method_id
 * @property int $user_id
 * @property Carbon|null $last_renewed_at
 * @property Carbon|null $subscribed_at
 * @property Carbon|null $expires_at
 * @property Carbon|null $canceled_at
 * @property bool $recurring
 * @property-read null|string $formatted_cost
 * @property-read null|string $formatted_expires_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \App\Models\Subscription\MembershipPlanRate $membershipPlanRate
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment\Payment[] $payments
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
class Subscription extends BaseModelAbstract implements HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * @var array All dates for the subscription
     */
    protected $dates = [
        'last_renewed_at',
        'subscribed_at',
        'expires_at',
        'canceled_at',
    ];

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
     * The payments that have been made for this subscription
     *
     * @return HasMany
     */
    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
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

    /**
     * Determines whether or not this subscription is good for a lifetime
     *
     * @return bool
     */
    public function isLifetime() : bool
    {
        return $this->membershipPlanRate->membershipPlan->duration == MembershipPlan::DURATION_LIFETIME;
    }

    /**
     * Formats the expires at date string
     *
     * @return null|string
     */
    public function getFormattedExpiresAtAttribute()
    {
        return $this->expires_at ? $this->expires_at->format('F jS Y') : null;
    }

    /**
     * Formats the cost to be human readable
     *
     * @return null|string
     */
    public function getFormattedCostAttribute()
    {
        return $this->membershipPlanRate && $this->membershipPlanRate->cost ?
            number_format((float)$this->membershipPlanRate->cost, 2) : null;
    }

    /**
     * Build the model validation rules
     * @param array $params
     * @return array
     */
    public function buildModelValidationRules(...$params): array
    {
        return [
            self::VALIDATION_RULES_BASE => [
                'cancel' => [
                    'boolean',
                ],
                'membership_plan_rate_id' => [
                    'integer',
                    Rule::exists('membership_plan_rates', 'id'),
                    MembershipPlanRateIsActiveValidator::KEY,
                ],
                'payment_method_id' => [
                    'integer',
                    Rule::exists('membership_plan_rates', 'id'),
                    MembershipPlanRateIsActiveValidator::KEY,
                ],
                'recurring' => [
                    'boolean',
                ],
            ],
            self::VALIDATION_RULES_CREATE => [
                self::VALIDATION_PREPEND_REQUIRED => [
                    'membership_plan_rate_id',
                    'payment_method_id',
                ],
            ],
            self::VALIDATION_RULES_UPDATE => [
                self::VALIDATION_PREPEND_NOT_PRESENT => [
                    'membership_plan_rate_id',
                ],
            ],
        ];
    }
}