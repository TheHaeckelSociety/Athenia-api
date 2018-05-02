<?php
/**
 * Has no rules
 */
declare(strict_types=1);

namespace App\Http\V1\Requests\Traits;

/**
 * Class HasNoRules
 * @package App\Http\V1\Requests\Traits
 */
trait HasNoRules
{
    /**
     * Default Rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}