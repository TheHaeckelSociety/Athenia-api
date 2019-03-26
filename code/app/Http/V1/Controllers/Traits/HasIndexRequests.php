<?php
declare(strict_types=1);

namespace App\Http\V1\Controllers\Traits;

use App\Http\V1\Requests\BaseRequestAbstract;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Trait HasIndexRequests
 *
 * Adds some functionality to a controller for dealing with index requests
 *
 * @package App\Http\V1\Controllers\Traits
 */
trait HasIndexRequests
{
    use ValidatesRequests, HasViewRequests;

    /**
     * Get the search statement
     *
     * @param BaseRequestAbstract $request
     * @return array
     */
    protected function filter(BaseRequestAbstract $request): array
    {
        return $request->input('cleaned_filter', []);
    }

    /**
     * Get the search statement
     *
     * @param BaseRequestAbstract $request
     * @return array
     */
    protected function search(BaseRequestAbstract $request): array
    {
        return $request->input('cleaned_search', []);
    }

    /**
     * Validate and get the limit for pagination per page
     *
     * @param BaseRequestAbstract $request
     * @return int
     */
    protected function limit(BaseRequestAbstract $request): int
    {
        $this->validate($request, [
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        return (int) $request->input('limit', 10);
    }
}