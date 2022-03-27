<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

class UserController extends Controller
{
    # Normal Users
    public function index()
    {
        $users = User::where("role", "user")->get();
        return response()->json($users);
    }

    public function show(int $id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function store(Request $request)
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

            return response()->json($e->getMessage());
        }

        return response()->json($user);
    }

    public function update(User $user)
    {
        $user->name = request()->name;
        $user->email = request()->email;
        $user->isbanned = false;
        $user->national_id = request()->national_id;
        $user->image_url = request()->image_url;

        try {
            $user->save();
        } catch (\Exception $e) {
            return response()->json($e);
        }

        return response()->json($user);
    }

    public function destroy(int $id)
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
        $cityManager = User::findOrFail($cityManagerId);
        $cityManager->update([
            "name" => $request->name,
            "email" => $request->email,
            "isbanned" => $request->isbanned,
            "password" => $request->password,
            "national_id" => $request->national_id,
            "image_url" => $request->image_url,
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
