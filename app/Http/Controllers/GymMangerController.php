<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;

class GymMangerController extends Controller
{

    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isCityManager');
    }

    public function index()
    {
        // $managers = User::whereHas('branch')->with('branch')->get();
        $managers = User::where('role', 'branch manager')->with('branch')->get();
        return response()->json($managers);
    }

    public function paginate()
    {
        $sortField = request('sortField', "created_at");
        if (!in_array($sortField, ['name', 'email', 'created_at']))
            $sortField = "created_at";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";
        try {
            $users = User::where("role", "branch manager")->when(request("search"), function ($q) {
                $q->where(function ($query) {
                    $query->where("name", "like", "%" . request("search") . "%")
                        ->orWhere("email", "like", "%" . request("search") . "%");
                });
            })->orderBy($sortField, $sortDirection)->paginate(5);
        } catch (\Throwable $th) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $users);
    }


    public function store(Request $request)
    {
        try {
            $manager = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'isbanned' => false,
                'password' => bcrypt($request->password),
                'national_id' => $request->national_id,
                'role' => "branch manager",
                'image_url' => $request->image_url,
                'branch_id' => $request->branch_id
            ]);
        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }
        $result = "branch manager added";
        return response()->json($result);
    }

    public function update(Request $request, $managerId)
    {
        $manager = User::findOrFail($managerId);
        if ($request->has('branch_id')) {
            $branchId = $request->branch_id;
        } else {
            $branchId = $manager->branch_id;
        }
        try {
            $manager->update([
                "name" => $request->name,
                "email" => $request->email,
                "isbanned" => $request->isbanned,
                "national_id" => $request->national_id,
                "image_url" => $request->image_url,
                "branch_id" => $branchId
            ]);
            $result = "branch manager updated";
            return response()->json($result);
        } catch (\Exception $e) {

            return response()->json($e);
        }
    }

    public function show($id)
    {
        $manager = User::find($id);
        return response()->json($manager);
    }

    public function destroy(int $managerId)
    {
        $message = "";
        if (!$manager = User::findOrFail($managerId))
            $message = "manager not found";
        try {
            $manager->delete();
        } catch (\Exception $e) {
            $message = "manager deleted successfully";
        }
        return response()->json($message);
    }
}
