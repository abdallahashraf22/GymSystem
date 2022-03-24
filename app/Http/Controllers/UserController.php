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
        $user->password = bcrypt(request()->password);
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


    # Branch Managers
    public function indexManagers()
    {
        // Here 1 is the City_Id as Static Value
        $branches = Branch::where("city_id", 1)->get("id");
        $managers = DB::table('users')
            ->select('*')
            ->where("role", "=", "branch manager")
            ->whereIn('branch_id', $branches)
            ->get();
        return response()->json($managers);
    }

    public function storeManager(Request $request)
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

        return response()->json($manager);
    }

    public function updateManager(Request $request, $managerId)
    {
        $manager = User::findOrFail($managerId);
        $manager->update([
            "name" => $request->name,
            "email" => $request->email,
            "isbanned" => $request->isbanned,
            "password" => $request->password,
            "national_id" => $request->national_id,
            "image_url" => $request->image_url,
            "branch_id" => $request->branch_id
        ]);
        $SuccessManagerUpdate = "Manager Updated Successfully";
        return response()->json($SuccessManagerUpdate);
    }

    public function destroyManager(int $managerId)
    {
        if (!$manager = User::findOrFail($managerId))
            return "not found";
        try {
            $manager->delete();
        } catch (\Exception $e) {
            return response()->json($e);
        }
        return response()->json(["isSuccess" => true]);
    }
}
