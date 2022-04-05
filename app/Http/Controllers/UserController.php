<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
    }

    # Normal Users
    public function index()
    {
        try {
            $users = User::where("role", "user")->get();
        } catch (\Exception $e) {

            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $users);
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
            $users = User::where("role", "user")->when(request("search"), function ($q) {
                $q->where(function ($query) {
                    $query->where("name", "like", "%" . request("search") . "%")
                        ->orWhere("email", "like", "%" . request("search") . "%");
                });
            })->orderBy($sortField, $sortDirection)->paginate(3);
        } catch (\Throwable $th) {
            return $this->createResponse(500, [], false, "server error");
        }

        $users = User::where("role", "user")->when(request("search"), function ($q) {
            $q->where(function ($query) {
                $query->where("name", "like", "%" . request("search") . "%")->orWhere("email", "like", "%" . request("search") . "%");
            });
        })->orderBy($sortField, $sortDirection)->paginate(5);

        //$users = User::where("role", "user")->get();
        return $this->createResponse(200, $users);
    }

    public function show(int $id)
    {
        try {
            $user = User::find($id);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $user);
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'isbanned' => false,
                'password' => bcrypt($request->password),
                'national_id' => $request->national_id,
                'role' => "user",
                'image_url' => $request->image_url,
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = request()->name;
        $user->email = request()->email;
        $user->isbanned = false;
        $user->national_id = request()->national_id;
        $user->image_url = request()->image_url;

        try {
            $user->save();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $user);
    }

    public function destroy(int $id)
    {

        if (!$user = User::find($id))
            return "not found";
        try {
            $user->isDeleted = true;
            $user->save();
        } catch (\Exception $e) {
            return response()->json($e);
        }
        // try {
        //     $user->delete();
        // } catch (\Exception $e) {
        //     return response()->json($e);
        // }
        return $this->createResponse(200, "deleted successfully");
    }


    # City Managers
    public function indexCityManagers()
    {
        $cityManagers = User::where("role", "city manager")->get();
        return response()->json($cityManagers);
    }

    public function showCityManager(int $id)
    {
        $cityManager = User::find($id);
        return response()->json($cityManager);
    }

    public function storeCityManager(Request $request)
    {
        try {
            $cityManager = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'isbanned' => false,
                'password' => bcrypt($request->password),
                'national_id' => $request->national_id,
                'role' => "city manager",
                'image_url' => $request->image_url,
            ]);
        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }

        return response()->json($cityManager);
    }

    public function updateCityManager(Request $request, int $cityManagerId)
    {
        // Make it Number here
        $cityManager = User::findOrFail($cityManagerId);
        $cityManager->update([
            "name" => $request->header("name"),
            "email" => $request->header("email"),
            // "isbanned" => $request->header("isbanned"),
            // "password" => $request->header("password"),
            "national_id" => $request->header("nationalid"),
            "image_url" => $request->header("imageurl"),
        ]);
        $SuccessCityManagerUpdate = "City Manager Updated Successfully";
        return response()->json($SuccessCityManagerUpdate);
    }

    public function destroyCityManager(int $id)
    {
        if (!$user = User::find($id))
            return "not found";
        try {
            $user->delete();
        } catch (\Exception $e) {
            return response()->json($e);
        }
        return response()->json(["isSuccess" => true]);
    }
}
