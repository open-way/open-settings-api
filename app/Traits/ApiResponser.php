<?php
/**
 * Created by PhpStorm.
 * User: UPN
 * Date: 14/04/2019
 * Time: 09:10
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponser
{
    function successResponse($data, $code) {
        // return $data->response()->setStatusCode($code);
        return response()->json(['data' => $data], $code);
    }

    function errorResponse($message, $code) {
        return response()->json(['error' => ['message' => $message, 'code' => $code]], $code);
    }

    function showAll($collection, $code = 200) {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }
        if ($collection instanceof Collection) {
            $collection = $this->paginateCollection($collection);
        }
        $resource = $collection->first()->resource;
        $transformedCollection = $resource::collection($collection);
        return $this->successResponse($transformedCollection, $code);
    }

    function showOne(Model $instance, $code) {
        $resource = $instance->resource;
        $transformedInstance = new $resource($instance);
        return $this->successResponse($transformedInstance, $code);
    }

    function showMessage($message, $code = 200)
    {
        return $this->successResponse($message, $code);
    }

    function paginateCollection(Collection $collection)
    {
        $perPage = $this->determinePageSize();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $results = $collection->slice(($page - 1) * $perPage, $perPage)
            ->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->query());
        return $paginated;
    }

    function determinePageSize()
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];
        $perPage = request()->validate($rules);
        return isset($perPage['per_page']) ? (int) $perPage['per_page'] : 5;
    }


}
