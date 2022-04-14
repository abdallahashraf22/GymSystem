<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityManager\CreateRequest;
use App\Http\Requests\CityManager\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CityManagerController extends Controller
{
    use ResponseTrait;

    # City Managers
    public function index()
    {
        try {
            $cityManagers = User::where("role", "city manager")->get();
        } catch (\Exception $e) {

            return $this->createResponse(500, [], false, "Server error");
        }
        return $this->createResponse(200, $cityManagers);
    }

    public function show(int $id)
    {

        try {
            $cityManager = User::find($id);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $cityManager);

    }

    public function store(CreateRequest $request)
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
            DB::table('cities')
                ->where('id', $request->city_id)
                ->update(['manager_id' => $cityManager->id]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "Server Error");
        }

        return $this->createResponse(200, $cityManager);
    }


    public function update(UpdateRequest $request, int $cityManagerId)
    {
        try {
            $cityManager = User::find($cityManagerId);
            $cityManager->update([
                'name' => $request->name,
                'email' => $request->email,
                'national_id' => $request->national_id,
                'image_url' => $request->image_url,
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        return $this->createResponse(200, $cityManager);
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
        return $this->createResponse(200, "Deleted Successfully");
    }

}
