<?php
declare(strict_types=1);

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

/**
 * Class OwnedByValidator
 * @package App\Validators
 */
class OwnedByValidator
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * OwnedByValidator constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * This is invoked by the validator rule 'owned_by'
     *
     * @param $attribute
     * @param $value
     * @param array $parameters
     * @param Validator|null $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters = [], Validator $validator = null)
    {
        /**
         * @var $ownerRequestParamName string the name of the variable in the request tht will hold the owner value
         * @var $relationName string the name of the relationship that should contain the value
         */
        list($ownerRequestParamName, $relationName) = $parameters;

        $ownerParam = $this->request->route($ownerRequestParamName);

        $relatedObject = $ownerParam->{$relationName};

        return $relatedObject->contains('id', $value);
    }
}