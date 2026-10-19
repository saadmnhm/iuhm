<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseApiController
{
    /**
     * Standard success response structure
     */
    protected function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        $response = ['status' => 'success'];
        
        if ($message !== 'Success') {
            $response['message'] = $message;
        }
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $status);
    }

    /**
     * Standard error response structure
     */
    protected function error(string $message = 'Error', int $status = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        return response()->json($response, $status);
    }

    /**
     * Standard paginated response structure
     */
    protected function paginated(LengthAwarePaginator $paginator, string $message = 'Success'): JsonResponse
    {
        return $this->success([
            'items' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ], $message);
    }

    /**
     * Get per_page from request with validation
     */
    protected function getPerPage(Request $request, int $default = 15, int $max = 100): int
    {
        $perPage = (int) $request->query('per_page', $default);
        return min(max($perPage, 1), $max);
    }

    /**
     * Get limit from request with validation (for non-paginated lists)
     */
    protected function getLimit(Request $request, int $default = 10, int $max = 50): int
    {
        $limit = (int) $request->query('limit', $default);
        return min(max($limit, 1), $max);
    }

    /**
     * Apply common filters to a query
     */
    protected function applyFilters($query, Request $request, array $filters = []): void
    {
        foreach ($filters as $filter => $column) {
            if ($request->filled($filter)) {
                $query->where($column, $request->$filter);
            }
        }
    }

    /**
     * Apply search to a query
     */
    protected function applySearch($query, Request $request, array $searchColumns = []): void
    {
        if ($request->filled('search') && !empty($searchColumns)) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $searchColumns) {
                foreach ($searchColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
    }

    /**
     * Apply sorting to a query
     */
    protected function applySort($query, Request $request, array $allowedSorts = [], string $defaultSort = 'created_at', string $defaultDirection = 'desc'): void
    {
        $sort = $request->query('sort_by', $defaultSort);
        $direction = $request->query('sort_dir', $defaultDirection);
        
        if (in_array($sort, $allowedSorts) && in_array(strtolower($direction), ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy($defaultSort, $defaultDirection);
        }
    }
}