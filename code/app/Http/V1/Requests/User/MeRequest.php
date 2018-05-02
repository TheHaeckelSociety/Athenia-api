<?php
/**
 * Validate that we can read ourselves
 */
declare(strict_types=1);

namespace App\Http\V1\Requests\User;

use App\Http\V1\Requests\BaseAuthenticatedRequestAbstract;
use App\Http\V1\Requests\Traits\HasNoRules;
use App\Models\User\User;
use App\Policies\UserPolicy;

/**
 * Class MeRequest
 * @package App\Http\V1\Requests\User
 */
class MeRequest extends BaseAuthenticatedRequestAbstract
{
    use HasNoRules;

    /**
     * Get the policy action for the guard
     *
     * @return string
     */
    protected function getPolicyAction(): string
    {
        return UserPolicy::ACTION_VIEW_SELF;
    }

    /**
     * Get the class name of the policy that this request utilizes
     *
     * @return string
     */
    protected function getPolicyModel(): string
    {
        return User::class;
    }

    /**
     * Gets any additional parameters needed for the policy function
     *
     * @return array
     */
    protected function getPolicyParameters(): array
    {
        return [auth()->user()];
    }
}
