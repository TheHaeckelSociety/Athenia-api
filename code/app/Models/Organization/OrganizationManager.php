<?php
declare(strict_types=1);

namespace App\Models\Organization;

use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Traits\HasValidationRules;
use Illuminate\Validation\Rule;

/**
 * Class OrganizationManager
 * @package App\Models\Organization
 */
class OrganizationManager extends BaseModelAbstract implements HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * Build the model validation rules
     * @param array $params
     * @return array
     */
    public function buildModelValidationRules(...$params): array
    {
        return [
            static::VALIDATION_RULES_BASE => [
                'role_id' => [
                    'required',
                    'integer',
                    Rule::exists('roles', 'id'),
                ],
                'email' => [
                    'string',
                    'email',
                ],
            ],
            static::VALIDATION_RULES_CREATE => [
                static::VALIDATION_PREPEND_REQUIRED => [
                    'email',
                ],
            ],
            static::VALIDATION_RULES_UPDATE => [
                static::VALIDATION_PREPEND_NOT_PRESENT => [
                    'email',
                ],
            ],
        ];
    }
}