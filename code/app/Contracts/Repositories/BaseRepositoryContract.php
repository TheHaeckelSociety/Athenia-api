<?php
declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\BaseModelAbstract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     * @param array $filters
     * @param array $searches
     * @param array $with
     * @param int|null $limit pass null to get all
     * @param array $belongsToArray array of models this should belong to
     * @param int $pageNumber
     * @return LengthAwarePaginator|Collection
     */
    public function findAll(array $filters = [], array $searches = [], array $with = [], $limit = 10, array $belongsToArray = [], int $pageNumber = 1);

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