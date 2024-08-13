<?php
namespace App\Utils;


class ResponsePaginationHelper {
    public static function getPaginationMetadata($paginator)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'first_item' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'last_item' => $paginator->lastItem(),
            'total' => $paginator->total(),
        ];
    }
}