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

    /**
     * Function to determine if a user has the ability to view all of a model
     *
     * @param User $user
     * @return bool
     */
    public function all(User $user)
    {
        return false;
    }

    /**
     * Function to determine if a user has the ability view a single model
     *
     * @param User $user
     * @param HasPolicyContract $model
     * @return boolean
     */
    public function view(User $user, HasPolicyContract $model)
    {
        return false;
    }

    /**
     * Function to determine if a user has the ability to create a model
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Function to determine if a user has the ability to update a model
     *
     * @param User $user
     * @param HasPolicyContract $model
     * @return boolean
     */
    public function update(User $user, HasPolicyContract $model)
    {
        return false;
    }

    /**
     * Function to determine if a user has the ability to delete a model
     *
     * @param User $user
     * @param HasPolicyContract $model
     * @return boolean
     */
    public function delete(User $user, HasPolicyContract $model)
    {
        return false;
    }
}