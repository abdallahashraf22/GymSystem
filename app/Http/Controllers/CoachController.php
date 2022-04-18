<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoachResource;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    use ResponseTrait, UploadImageTrait;

    public function index()
    {
        $coach = Coach::get();
        return CoachResource::collection($coach);
        return response()->json($coach);
    }

    public function show($id)
    {
        $coach = Coach::find($id);
        return response()->json($coach);
    }

    public function store(Request $request)
    {
        $imageName = $this->uploadImage("coaches", $request->file('image'));
        try {
            Coach::create([
                'name' => $request->name,
                'image_url' => $imageName
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        $success_message = "Coach was created successfully";
        return response()->json($success_message);
    }

    public function destroy($id)
    {
        $message = "Coach was not found";
        if (!Coach::find($id)) {
            return response()->json($message);
        }
        try {
            $coach = Coach::findOrFail($id);
            $coach->update([
                'isDeleted' => true
            ]);
            $message = "Deleted Successfully";
            return response()->json($message);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);
        $imageName = $this->uploadImage("coaches", $request->file('image'));
        $coach->update([
            'name' => $request->name,
            'image_url' => $imageName
        ]);
        $success_message = "Coach was updated successfully";
        return response()->json($success_message);
    }

    public function paginate()
    {
        $sortField = request('sortField', "name");
        if (!in_array($sortField, ['name']))
            $sortField = "name";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";

        try {
            $coaches = Coach::when(request("search"), function ($q) {
                $q->where(function ($query) {
                    $query->where("name", "like", "%" . request("search") . "%");
            
                });
            })->orderBy($sortField, $sortDirection)->paginate(5);
        } catch (\Throwable $th) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $coaches;
    }

}
