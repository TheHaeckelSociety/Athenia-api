<?php
declare(strict_types=1);

namespace App\Http\V1\Middleware;

use Closure;
use Illuminate\Support\Str;
use App\Exceptions\ValidationException;

/**
 * Class SearchFilterParsingMiddleware
 * @package App\Http\V1\Middleware
 */
class SearchFilterParsingMiddleware
{
    /**
     * Add a parsed filter and search to the request as the `refine` variable
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ValidationException
     */
    public function handle($request, Closure $next)
    {
        $refine = [];
        
        if ($filters = $request->query('filter')) {
            if (is_array($filters)) {
                foreach ($filters as $key => $filter) {
                    $refine[] = [$key, '=', urldecode($filter)];
                }
            }            
        }
        
        if ($search = $request->query('search')) {
            if (is_array($search)) {
                foreach ($search as $key => $searchTermContainer) {

                    if (is_array($searchTermContainer)) {

                        if (!isset($refine[$key])) {
                            $refine[$key] = [];
                        }

                        foreach ($searchTermContainer as $individualSearch) {
                            $refine[$key] = $this->createSearchTerm($key, $individualSearch, $refine[$key]);
                        }
                    } else {

                        $refine = $this->createSearchTerm($key, $searchTermContainer, $refine);
                    }
                }
            }
        }

        if ($refine) $request->query->set('refine', $refine);

        return $next($request);
    }

    /**
     * Creates a search term, and returns the array with the new search term added
     *
     * @param $key
     * @param $searchTermContainer
     * @param $refine
     * @return array
     * @throws ValidationException
     */
    private function createSearchTerm($key, $searchTermContainer, $refine)
    {
        $parts = explode(',', $searchTermContainer, 2);
        if (empty($parts[1])) {
            throw new ValidationException(sprintf('Search term [%s] is missing a value.', $key));
        }

        $searchTerm = $parts[1];

        switch ($parts[0]) {
            case 'gt': $searchType = '>'; break;
            case 'gte': $searchType = '>='; break;
            case 'lt': $searchType = '<'; break;
            case 'lte': $searchType = '<='; break;
            case 'eq': $searchType = '='; break;
            case 'ne': $searchType = '!='; break;

            case 'in': $searchType = 'in'; $searchTerm = explode(',', $searchTerm); break;
            case 'notin': $searchType = 'not in'; $searchTerm = explode(',', $searchTerm); break;

            case 'like':
                $searchType = 'like';
                if (Str::startsWith($searchTerm, '*')) $searchTerm = '%' . substr($searchTerm, 1);
                if (Str::endsWith($searchTerm, '*')) $searchTerm = substr($searchTerm, 0, -1) . '%';
                break;

            case 'between': $searchType = 'between'; $searchTerm = explode(',', $searchTerm); break;

            default:
                throw new ValidationException(sprintf('Search term [%s] is has an invalid qualifier [%s]', $key, $parts[0]));
        }

        if ($searchType == 'between') { // I wish I could figure out how to get the 'between' operator to work - but it has a hardcoded $type = 'basic' in the where()
            $refine[] = [$key, '>=', urldecode($searchTerm[0])];
            $refine[] = [$key, '<=' , urldecode($searchTerm[1])];
        }
        elseif ($searchType == 'like') {
            $refine[] = [$key, $searchType, $searchTerm];
        }
        else {
            $refine[] = [$key, $searchType, is_array($searchTerm) ? array_map('urldecode', $searchTerm) : urldecode($searchTerm)];
        }

        return $refine;
    }
}
