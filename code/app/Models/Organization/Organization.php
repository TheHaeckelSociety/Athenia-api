<?php
declare(strict_types=1);

namespace App\Models\Organization;

use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Traits\HasValidationRules;

/**
 * Class Organization
 * @package App\Models\Organization
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