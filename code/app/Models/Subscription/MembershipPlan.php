<?php
declare(strict_types=1);

namespace App\Models\Subscription;

use App\Contracts\Models\HasPolicyContract;
use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Traits\HasValidationRules;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

/**
 * Class Plan
 *
 * @package App\Models\MembershipPlan
 * @property int $id
 * @property float|null $current_cost
 * @property string $name
 * @property string $duration
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subscription\MembershipPlanRate[] $membershipPlanRates
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipPlan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MembershipPlan extends BaseModelAbstract implements HasPolicyContract, HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * @var string the enum value for the duration field when the plan only lasts a year
     */
    const DURATION_YEAR = 'year';

    /**
     * @var string the enum value for the duration field when the plan only lasts a month
     */
    const DURATION_MONTHLY = 'monthly';

    /**
     * @var string the enum value for the duration field when the plan lasts forever
     */
    const DURATION_LIFETIME = 'lifetime';

    /**
     * The available duration types for a membership plan
     */
    const AvailableDurations = [
        MembershipPlan::DURATION_MONTHLY,
        MembershipPlan::DURATION_YEAR,
        MembershipPlan::DURATION_LIFETIME,
    ];

    /**
     * Values that are appending on a toArray function call
     *
     * @var array
     */
    protected $appends = [
        'current_cost',
    ];

    /**
     * All membership plan rates that have
     *
     * @return HasMany
     */
    public function membershipPlanRates(): HasMany
    {
        return $this->hasMany(MembershipPlanRate::class);
    }

    /**
     * All subscriptions that have been signed up for this membership plan
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Function that creates the current cost attribute
     *
     * @return null|float
     */
    public function getCurrentCostAttribute()
    {
        /** @var MembershipPlanRate $currentRate */
        $currentRate = $this->membershipPlanRates()
            ->where('active', true)
            ->orderBy('created_at', 'DESC')->first();

        return $currentRate ? $currentRate->cost : null;
    }

    /**
     * Build the model validation rules
     * @param array $params Any additional parameters needed
     * @return array
     */
    public function buildModelValidationRules(...$params): array
    {
        return [
            self::VALIDATION_RULES_BASE => [

                'name' => [
                    'string',
                    'max:120',
                ],

                'current_cost' => [
                    'numeric',
                    'min:0.00',
                    'max:999999.99',
                ],

                'duration' => [
                    'string',
                    Rule::in(MembershipPlan::AvailableDurations),
                ],
            ],
            self::VALIDATION_RULES_CREATE => [
                self::VALIDATION_PREPEND_REQUIRED => [
                    'name',
                    'current_cost',
                    'duration',
                ],
            ],
            self::VALIDATION_RULES_UPDATE => [
                self::VALIDATION_PREPEND_NOT_PRESENT => [
                    'duration',
                ],
            ],
        ];
    }

    /**
     * Swagger definition below
     *
     * @SWG\Definition (
     *     type="object",
     *     definition="MembershipPlan",
     *     description="The details of a membership plan",
     *     @SWG\Property(
     *         property="id",
     *         type="integer",
     *         format="int32",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="UTC date of the time this was created",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="UTC date of the time this was updated",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="current_cost",
     *         type="number",
     *         description="The current cost of the membership plan"
     *     ),
     *     @SWG\Property(
     *         property="duration",
     *         type="string",
     *         maxLength=128,
     *         description="The duration for this membership plan"
     *     ),
     *     @SWG\Property(
     *         property="subscriptions",
     *         description="The subscriptions attatched to this membership plan",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Subscription")
     *     ),
     * )
     */
}