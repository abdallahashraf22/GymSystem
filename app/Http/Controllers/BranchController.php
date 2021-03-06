<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\CreateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Traits\PaginatorTrait;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use ResponseTrait, PaginatorTrait, UploadImageTrait;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('isCityManager');
    }

    public function index()
    {

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

        $sortField = request('sortField', "created_at");
        if (!in_array($sortField, ['name', 'created_at']))
            $sortField = "created_at";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";
        // select * from branches where city_id=1;
        try {
            $branches = Branch::when(request("city_id") != "all", function ($query) {
                $query->where('city_id', request("city_id"));
            })->when(request("search"), function ($q) {
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

    public function store(CreateBranchRequest $request)
    {
        $imageName = $this->uploadImage("branches", $request->file('image'));
        try {
            $branch = Branch::create([
                'name' => $request->name,
                'city_id' => $request->city_id,
                'img' => $imageName,
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $branch);
    }

    public function update(CreateBranchRequest $request, Branch $branch)
    {
        $imageName = $this->uploadImage("branches", $request->file('image'));

        $branch->name = $request->name;
        $branch->city_id = $request->city_id;
        $branch->img = $imageName;

        try {
            $branch->save();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $branch);
    }

    public function show(int $id)
    {
        try {
            $branch = Branch::find($id);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $branch);
    }

    public function destroy(int $id)
    {

        if (!$branch = Branch::find($id))
            return "not found";
        try {
            $branch->isDeleted = true;
            $branch->save();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, "deleted successfully");
    }
}
