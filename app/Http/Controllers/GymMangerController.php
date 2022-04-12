<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GymMangerController extends Controller
{

    public function __construct()
    {
        $this->middleware('isCityManager');
    }

    public function index()
    {
        // $managers = User::whereHas('branch')->with('branch')->get();
        $managers = User::where('role', 'branch manager')->with('branch')->get();
        return response()->json($managers);
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
        return response()->json($manager);
    }

    public function update(Request $request, $managerId)
    {
        $manager = User::findOrFail($managerId);
        try {
            $manager->update([
                "name" => $request->name,
                "email" => $request->email,
                "isbanned" => $request->isbanned,
                "national_id" => $request->national_id,
                "image_url" => $request->image_url,
                "branch_id" => $request->branch_id
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
