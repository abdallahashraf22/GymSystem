<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CityController extends Controller
{

    public function index()
    {
//      $cities = DB::table('cities')->get();
        $cities = DB::table('cities')
            ->join('users', 'cities.manager_id', '=', 'users.id')
            ->select('cities.*', 'users.name as UserName')
            ->get();

        return response()->json($cities);
    }


    public function store(Request $request)
    {
        try {
            $city = City::create([
                'name' => $request->name,
                'manager_id' => $request->manager_id,

            ]);
        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }

        return response()->json($city);
    }


    public function show($cityId)
    {
        $city = City::findOrFail($cityId);
        return response()->json($city);
    }


    public function update(Request $request, $cityId)
    {
        // Make it Number here
        $city = City::findOrFail($cityId);
        $city->update([
            "name" => $request->header("name"),
            "manager_id" => $request->header("managerId"),
        ]);
        $SuccessCityUpdate = "City Updated Successfully";
        return response()->json($SuccessCityUpdate);
    }


    public function destroy($id)
    {
        if (!$city = City::find($id))
            return "not found";
        try {
            $city->delete();
        } catch (\Exception $e) {
            return response()->json($e);
        }
        return response()->json(["isSuccess" => true]);
    }
}
