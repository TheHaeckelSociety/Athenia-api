<?php
declare(strict_types=1);

namespace App\Repositories;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface as LogContract;
use App\Contracts\Repositories\BaseRepositoryContract;
use App\Exceptions\NotImplementedException;
use App\Models\BaseModelAbstract;

/**
 * Class BaseRepositoryAbstract
 * @package App\Repositories
 */
abstract class BaseRepositoryAbstract implements BaseRepositoryContract
{
    /**
     * @var BaseModelAbstract
     */
    protected $model;

    /**
     * @var LogContract
     */
    protected $log;

    /**
     * BaseRepositoryAbstract constructor.
     * @param $model
     * @param LogContract $log
     */
    public function __construct($model, LogContract $log)
    {
        $this->model = $model;
        $this->log = $log;
    }
    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param $id
     * @param array [$with] relationships to load eagerly
     *
     * @throws ModelNotFoundException
     *
     * @return BaseModelAbstract
     */
    public function findOrFail($id, array $with = [])
    {
        return $this->model->with($with)->findOrFail($id);
    }

    /**
     * Builds the find all query
     *
     * @param array $where
     * @param array $with
     * @param array $belongsToArray
     * @return QueryBuilder
     */
    protected function buildFindAllQuery(array $where = [], array $with = [], array $belongsToArray = [])
    {
        $result = $this->model->with($with);

        foreach ($belongsToArray as $parentModel) {
            $parentModelPluralFunction = $this->getRelationshipFunctionName($parentModel, $this->model);

            $parentRelationship = $this->model->$parentModelPluralFunction();

            switch (true) {
                case ($parentRelationship instanceof BelongsTo):
                    $queryKey = $parentRelationship->getQualifiedForeignKeyName();
                    $parentModelKeyField = $parentRelationship->getOwnerKeyName();

                    break;

                case ($parentRelationship instanceof BelongsToMany):
                    $queryKey = $parentRelationship->getQualifiedRelatedPivotKeyName();
                    $parentModelKeyField = $parentRelationship->getRelated()->getKeyName();
                    break;

                // @codeCoverageIgnoreStart
                // this is just in case some other relationship gets introduced
                default:
                    throw new NotImplementedException('A relationship has not yet been handled.');
                // @codeCoverageIgnoreEnd
            }

            $parentModelValue = $parentModel->$parentModelKeyField;
            $result->whereHas($parentModelPluralFunction, function($query) use ($queryKey, $parentModelValue) {
                $query->where($queryKey, '=', $parentModelValue);
            });
        }

        return $result->where($where);
    }

    /**
     * Find all
     *
     * @param array $where
     * @param array $with
     * @param int $limit
     * @param array $belongsToArray array of models that this could belong to
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws NotImplementedException
     */
    public function findAll(array $where = [], array $with = [], int $limit = 10, array $belongsToArray = [])
    {
        return $this->buildFindAllQuery($where, $with, $belongsToArray)->paginate($limit)->appends(Input::except('page'));
    }

    /**
     * Save a new instance of this model, and then return the instance
     *
     * In cases where we want to force the data, pass in forcedvalues - this is rare.
     *
     * @param array $data
     * @param BaseModelAbstract $relatedModel if there is a parent to assign this to
     * @param array $forcedValues
     * @return BaseModelAbstract
     * @throws NotImplementedException
     */
    public function create(array $data = [], BaseModelAbstract $relatedModel = null, array $forcedValues = [])
    {
        $newModel = $this->model->newInstance($data);

        foreach ($forcedValues as $key => $value) {
            $newModel->{$key} = $value;
        }

        if ($relatedModel) {
            $relatedModelPluralFunction = $this->getRelationshipFunctionName($relatedModel, $this->model);

            $relationship = $this->model->$relatedModelPluralFunction();

            switch (true) {
                case ($relationship instanceof BelongsTo):
                    $parentKey = $relationship->getForeignKeyName();
                    $parentIdKey = $relationship->getOwnerKeyName();
                    $newModel->$parentKey = $relatedModel->$parentIdKey;
                    break;

                case ($relationship instanceof BelongsToMany):
                    $newModel->save(); // need to do this to get the ID

                    $newModel->$relatedModelPluralFunction()->attach($relatedModel->{$relatedModel->getKeyName()});
                    break;

                case ($relationship instanceof HasOne):
                case ($relationship instanceof HasMany):
                    $newModel->save();

                    $ownerKey = $relationship->getForeignKeyName();
                    $localKey = explode('.', $relationship->getQualifiedParentKeyName())[1];

                    $relatedModel->$ownerKey = $newModel->$localKey;
                    $relatedModel->save();
                    break;

                // @codeCoverageIgnoreStart
                // this is just in case some other relationship gets introduced
                default:
                    throw new NotImplementedException('A relationship has not yet been handled.');
                // @codeCoverageIgnoreEnd
            }
        }

        if (!$newModel->wasRecentlyCreated) $newModel->save(); // because one of the parent model relationships saves it first.

        $this->log->info('Created model', ['model_id'=>$newModel->id, 'model' => get_class($newModel)]);
        return $newModel;
    }

    /**
     * Update the model
     *
     * @param BaseModelAbstract $model
     * @param array $data
     * @param array $forcedValues
     * @return BaseModelAbstract
     */
    public function update(BaseModelAbstract $model, array $data, array $forcedValues = []): BaseModelAbstract
    {
        if ($forcedValues) $model->forceFill($forcedValues);
        if (!$model->update($data)) throw new \DomainException(sprintf('%s[%d] failed to update', get_class($model), $model->id));
        $this->log->info('Updated model', ['model_id' => $model->id, 'model' => get_class($model)]);
        return $model;
    }

    /**
     * Delete this single model
     * 
     * @param BaseModelAbstract $model
     * @return bool|null
     */
    public function delete(BaseModelAbstract $model)
    {
        if (!$model->delete()) throw new \DomainException(sprintf('%s[%d] failed to delete', get_class($model), $model->id));
        $this->log->info('Deleted model', ['model_id' => $model->id, 'model' => get_class($model)]);
        return true;
    }

    /**
     * Try to build the relationship model function name
     *
     * @param BaseModelAbstract $model
     * @param BaseModelAbstract $parentModel
     * @return string
     */
    protected function getRelationshipFunctionName(BaseModelAbstract $model, BaseModelAbstract $parentModel): string
    {
        $method = Str::camel(Str::plural(class_basename($model)));
        if (!method_exists($parentModel, $method)) {
            $method = Str::camel(class_basename($model));
        }
        return $method;
    }
}