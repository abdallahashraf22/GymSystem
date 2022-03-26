<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityManagerController extends Controller
{
    // City Branches
    public function getAllBranches()
    {
        // Here we will get Loggedin Manager ID

        $managed_city = City::select("*")
            ->where("id", "=", "1")
            ->first();
        return response()->json($managed_city->branches);
    }

    public function createBranch(Request $request)
    {
        Branch::create([
            "id" => $request->id,
            "name" => $request->name,
            "city_id" => $request->city_id,
        ]);
        $SuccessBranchCreation = "Branch Created Successfully";
        return response()->json($SuccessBranchCreation);
    }

    public function editBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update([
            "id" => $id,
            "name" => $request->name,
            "city_id" => $request->city_id
            // There is a Problem here in key city_id
            // "city_id"=>$request->header('city_id')
        ]);
        $SuccessBranchUpdate = "Branch Updated Successfully";
        return response()->json($SuccessBranchUpdate);
    }

    public function deleteBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        $SuccessBranchDelete = "Branch Deleted Successfully";
        return response()->json($SuccessBranchDelete);
    }
    // Branches Mangers
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
