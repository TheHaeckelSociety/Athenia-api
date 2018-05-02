<?php
declare(strict_types=1);

namespace App\Contracts\Policies;

use App\Contracts\Models\HasPolicyContract;
use App\Models\User\User;

/**
 * Interface BasePolicyContract
 * @package App\Contracts\Policies
 */
interface BasePolicyContract
{
    /**#@+
     * @var string action method names for the policies
     */
    const ACTION_LIST = 'all';
    const ACTION_VIEW = 'view';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    /**#@-*/

    /**
     * Called before any other policy functions are attempted on the model.
     * Does not continue if
     *
     * @param User $user
     * @return mixed
     */
    public function before(User $user);

    /**
     * Function to determine if a user has the ability to view all of a model
     *
     * @param User $user
     * @return bool
     */
    public function all(User $user);

    /**
     * Function to determine if a user has the ability view a single model
     *
     * @param User $user
     * @param HasPolicyContract $model
     * @return boolean
     */
    public function view(User $user, HasPolicyContract $model);

    /**
     * Function to determine if a user has the ability to create a model
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user);

    /**
     * Function to determine if a user has the ability to update a model
     *
     * @param User $user
     * @param HasPolicyContract $model
     * @return boolean
     */
    public function update(User $user, HasPolicyContract $model);

    /**
     * Function to determine if a user has the ability to delete a model
     *
     * @param User $user
     * @param HasPolicyContract $model
     * @return boolean
     */
    public function delete(User $user, HasPolicyContract $model);
}