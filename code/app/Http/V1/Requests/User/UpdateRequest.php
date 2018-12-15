<?php
declare(strict_types=1);

namespace App\Http\V1\Requests\User;

use App\Http\V1\Requests\BaseAuthenticatedRequestAbstract;
use App\Models\User\User;
use App\Policies\UserPolicy;

/**
 * Class UpdateRequest
 * @package App\Http\V1\Requests\User
 */
class UpdateRequest extends BaseAuthenticatedRequestAbstract
{
    /**
     * Get the policy action for the guard
     *
     * @return string
     */
    protected function getPolicyAction(): string
    {
        return UserPolicy::ACTION_UPDATE;
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
        return [$this->route('user')];
    }

    /**
     * Gets the validation rules needed for this request
     *
     * @param User $user
     * @return array
     */
    public function rules(User $user): array
    {
        return $user->getValidationRules(User::VALIDATION_RULES_UPDATE, $this->route('user'));
    }
}