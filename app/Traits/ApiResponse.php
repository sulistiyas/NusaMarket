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
        // Paginator can be LengthAwarePaginator or ResourceCollection
        $items = method_exists($paginator, 'items') ? $paginator->items() : $paginator;
        
        $currentPage = method_exists($paginator, 'currentPage') ? $paginator->currentPage() : 1;
        $lastPage = method_exists($paginator, 'lastPage') ? $paginator->lastPage() : 1;
        $perPage = method_exists($paginator, 'perPage') ? $paginator->perPage() : count($items);
        $total = method_exists($paginator, 'total') ? $paginator->total() : count($items);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $items,
            'meta'    => [
                'current_page' => $currentPage,
                'last_page'    => $lastPage,
                'per_page'     => $perPage,
                'total'        => $total,
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
