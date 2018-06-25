<?php
declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\BaseModelAbstract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface BaseRepositoryContract
 * @package App\Contracts\Repositories
 */
interface BaseRepositoryContract
{
    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param $id
     * @param array [$with] relationships to load eagerly
     *
     * @return BaseModelAbstract
     */
    public function findOrFail($id, array $with = []);

    /**
     * Find all
     *
     * @param array $where
     * @param array $with
     * @param int $limit
     * @param array $belongsToArray array of models this should belong to
     * @return LengthAwarePaginator
     */
    public function findAll(array $where = [], array $with = [], int $limit = 10, array $belongsToArray = []);

    /**
     * Save a new instance of this model, and then return the instance
     *
     * @param array $data
     * @param BaseModelAbstract $relatedModel if there is a relationship to build
     * @param array $forcedValues
     * @return BaseModelAbstract
     */
    public function create(array $data = [], BaseModelAbstract $relatedModel = null, array $forcedValues = []);

    /**
     * Update the model
     *
     * @param BaseModelAbstract $model
     * @param array $data
     * @param array $forcedValues
     * @return BaseModelAbstract
     */
    public function update(BaseModelAbstract $model, array $data, array $forcedValues = []): BaseModelAbstract;

    /**
     * Delete this single model
     *
     * @param BaseModelAbstract $model
     * @return bool|null
     */
    public function delete(BaseModelAbstract $model);
}