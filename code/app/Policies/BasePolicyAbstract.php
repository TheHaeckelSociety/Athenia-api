<?php
declare(strict_types=1);

namespace App\Policies;

use App\Contracts\Models\HasPolicyContract;
use App\Contracts\Policies\BasePolicyContract;
use App\Models\User\User;

/**
 * Class BasePolicyAbstract
 * @package App\Policies
 */
abstract class BasePolicyAbstract implements BasePolicyContract
{
    /**
     * No one in this app should be able to see everything
     *
     * @param User $user
     * @return null
     */
    public function before(User $user)
    {
        return null;
    }
}