<?php
declare(strict_types=1);

namespace App\Repositories\User;

use Psr\Log\LoggerInterface as LogContract;
use App\Contracts\Repositories\User\UserRepositoryContract;
use App\Models\User\User;
use App\Repositories\BaseRepositoryAbstract;
use App\Repositories\Traits\NotImplemented;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository extends BaseRepositoryAbstract implements UserRepositoryContract
{
    use NotImplemented\FindOrFail, NotImplemented\Delete, NotImplemented\FindAll;

    /**
     * UserRepository constructor.
     * @param User $model
     * @param LogContract $log
     */
    public function __construct(User $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }

    /**
     * Attempts to look up a user by email address, and returns null if we cannot find one
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Swagger definitions below...
     *
     * @SWG\Definition(
     *     definition="Users",
     *     @SWG\Property(
     *         property="data",
     *         description="A list of user models",
     *         type="array",
     *         minItems=0,
     *         maxItems=100,
     *         uniqueItems=true,
     *         @SWG\Items(ref="#/definitions/User")
     *     )
     * )
     */
}