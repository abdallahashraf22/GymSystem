<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Http\Resources\SessionResource;

class PackageController extends Controller
{
    public function index()
    {
        $package = Package::get();
        return response()->json($package);
    }
    public function show($id)
    {
        $package = Package::find($id);
        return response()->json($package);
    }

    public function store(Request $request)
    {
        try {
            Package::create([
                'name' => $request->name,
                'price' => $request->price,
                'number_of_sessions' => $request->number_of_sessions,
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        $success_message = "Package was created successfully";
        return response()->json($success_message);
    }

    public function destroy($id)
    {
        $message = "Package was not found";
        if (!Package::find($id)) {
            return response()->json($message);
        }

        try {
            $session = Package::findOrFail($id);
            $session->delete($id);
            $message = "Package Deleted Successfully";
            return response()->json($message);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(Request $request, $package_id)
    {
        $session = Package::findOrFail($package_id);

        $session->update([
            'name' => $request->name,
            'price' => $request->price,
            'number_of_sessions' => $request->number_of_sessions,
        ]);
        $success_message = "Package was updated successfully";
        return response()->json($success_message);
    }
}
