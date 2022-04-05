<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchResource;
use App\Http\Traits\PaginatorTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use ResponseTrait, PaginatorTrait;

    public function __construct()
    {
        $this->middleware('isCityManager')->only(['paginate', 'index']);
    }

    public function index()
    {
        $city_id = request('city_id', 1);

        $sortField = request('sortField', "created_at");
        if (!in_array($sortField, ['name', 'created_at']))
            $sortField = "created_at";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";

        try {
            $branches = Branch::when(request("city_id") != "all", function ($q) {
                $q->where(function ($query) {
                    $query->where('city_id', request("city_id"));
                });
            })
                ->when(request("search"), function ($q) {
                    $q->where(function ($query) {
                        $query->where("name", "like", "%" . request("search") . "%");
                    });
                })->with('city')->orderBy($sortField, $sortDirection)->get();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $branches);
    }

    public function paginate()
    {
        $city_id = request('city_id', 1);

        $sortField = request('sortField', "created_at");
        if (!in_array($sortField, ['name', 'created_at']))
            $sortField = "created_at";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";

        try {
            $branches = Branch::where('city_id', $city_id)
                ->when(request("search"), function ($q) {
                    $q->where(function ($query) {
                        $query->where("name", "like", "%" . request("search") . "%");
                    });
                })->with('city.manager')->orderBy($sortField, $sortDirection)->paginate(5);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }



        $response = [
            "data" => BranchResource::collection($branches),
            "links" => $this->createPaginationLinks($branches->total(), 5)
        ];
        return $this->createResponse(200, $response);
    }
}
