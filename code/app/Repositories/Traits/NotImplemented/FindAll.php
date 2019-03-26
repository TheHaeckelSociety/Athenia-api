<?php
declare(strict_types=1);

namespace App\Repositories\Traits\NotImplemented;

use App\Exceptions\NotImplementedException;

/**
 * Class FindAll
 * @package App\Repositories\Traits\NotImplemented
 */
trait FindAll
{
    /**
     * Not Implemented
     *
     * @param array $filter
     * @param array $search
     * @param array $with
     * @param int $limit
     * @param array $belongsToArray
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|void
     */
    public function findAll(array $filter = [], array $search = [], array $with = [], int $limit = 10, array $belongsToArray = [])
    {
        throw new NotImplementedException();
    }
}