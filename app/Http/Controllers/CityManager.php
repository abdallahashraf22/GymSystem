<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class CityManager extends Controller
{
    public function getAllBranches()
    {
        // Here we will get Loggedin Manager ID

        $managed_city = City::select("*")
            ->where("manager_id", "=", "1")
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
            "city_id"=> $request->city_id
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

}
