<?php
declare(strict_types=1);

namespace App\Models\Organization;

use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Traits\HasValidationRules;
use Eloquent;
use Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class Organization
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @method static EloquentJoinBuilder|Organization newModelQuery()
 * @method static EloquentJoinBuilder|Organization newQuery()
 * @method static EloquentJoinBuilder|Organization query()
 * @method static Builder|Organization whereCreatedAt($value)
 * @method static Builder|Organization whereDeletedAt($value)
 * @method static Builder|Organization whereId($value)
 * @method static Builder|Organization whereName($value)
 * @method static Builder|Organization whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Organization extends BaseModelAbstract implements HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * @param mixed ...$params
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
            ],
            self::VALIDATION_RULES_CREATE => [
                self::VALIDATION_PREPEND_REQUIRED => [
                    'name',
                ],
            ],
        ];
    }
}