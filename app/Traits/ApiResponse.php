<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function paginated($paginator, string $message = 'Success')
    {
        // Paginator can be LengthAwarePaginator or ResourceCollection wrapping LengthAwarePaginator
        $target = $paginator;
        if (is_object($paginator) && property_exists($paginator, 'resource') && is_object($paginator->resource)) {
            $target = $paginator->resource;
        }

        $items = method_exists($paginator, 'items') ? $paginator->items() : $paginator;
        
        $currentPage = method_exists($target, 'currentPage') ? $target->currentPage() : 1;
        $lastPage = method_exists($target, 'lastPage') ? $target->lastPage() : 1;
        $perPage = method_exists($target, 'perPage') ? $target->perPage() : (is_countable($items) ? count($items) : 10);
        $total = method_exists($target, 'total') ? $target->total() : (is_countable($items) ? count($items) : 0);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $items,
            'meta'    => [
                'current_page' => (int) $currentPage,
                'last_page'    => (int) $lastPage,
                'per_page'     => (int) $perPage,
                'total'        => (int) $total,
            ],
        ]);
    }

    protected function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
