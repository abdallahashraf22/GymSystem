<?php

namespace App\Http\Traits;


trait PaginatorTrait
{
    public function createPaginationLinks($total, $perPage)
    {
        $links = [];
        for ($i = 1; $i <= $total / $perPage; $i++) {
            $link = ["label" => $i];
            array_push($links, $link);
        }
        return $links;
    }
}
