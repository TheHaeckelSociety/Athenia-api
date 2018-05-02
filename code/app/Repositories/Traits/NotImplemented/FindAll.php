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
     * @param array $where
     * @param array $with
     * @param int $limit
     * @param array $belongsToArray
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|void
     * @throws NotImplementedException
     */
    public function findAll(array $where = [], array $with = [], int $limit = 10, array $belongsToArray = [])
    {
        throw new NotImplementedException();
    }
}