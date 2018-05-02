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
     * Get the refine / where statement
     *
     * @param BaseRequestAbstract $request
     * @return array
     */
    protected function refine(BaseRequestAbstract $request): array
    {
        return $request->input('refine', []);
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