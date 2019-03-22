<?php
declare(strict_types=1);

namespace App\Policies;

use App\Contracts\Policies\BasePolicyContract;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class RolePolicy
 * @package App\Policies
 */
class RolePolicy extends BasePolicyAbstract implements BasePolicyContract
{
    use HandlesAuthorization;
}