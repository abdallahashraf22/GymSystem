<?php

namespace App\Http\Traits;


trait PaginatorTrait
{
    public function createPaginationLinks($total, $perPage)
    {
        $links = [];
        $pagesCount = ceil($total / $perPage);

        for ($i = 1; $i <= $pagesCount; $i++) {
            $link = ["label" => $i];
            array_push($links, $link);
        }
        return $links;
    }
}
