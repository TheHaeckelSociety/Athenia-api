<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Contracts\Repositories\User\ContactRepositoryContract;
use App\Models\User\Contact;
use App\Models\User\User;
use App\Repositories\BaseRepositoryAbstract;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class ContactRepository
 * @package App\Repositories\User
 */
class ContactRepository extends BaseRepositoryAbstract implements ContactRepositoryContract
{
    /**
     * ContactRepository constructor.
     * @param Contact $model
     * @param LogContract $log
     */
    public function __construct(Contact $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }

    /**
     * @param array $where
     * @param array $with
     * @param int $limit
     * @param array $belongsToArray
     * @param int $pageNumber
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function findAll(array $filter = [], array $search = [], array $with = [], $limit = 10, array $belongsToArray = [], int $pageNumber = 1)
    {
        $query = parent::buildFindAllQuery($filter, $search, $with, []);

        /** @var User $user */
        foreach ($belongsToArray as $user) {
            $query->where('initiated_by_id', $user->id);
            $query->orWhere('requested_id', $user->id);
        }

        return $query->paginate($limit, $columns = ['*'], $pageName = 'page', $pageNumber);
    }
}