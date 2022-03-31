<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Http\Resources\SessionResource;
use Illuminate\Support\Facades\Log;

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

        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        // dd($ext);
        $imageName = "assets/images/packages/" .  uniqid() . ".$ext";
        $image->move(public_path('assets/images/packages'), $imageName);
        // dd($data, $name);

        $request->validate([
            'name' => ['required', 'min:3'],
            'price' => ['required'],

        ]);

        try {
            Package::create([
                'name' => $request->name,
                'price' => $request->price,
                'number_of_sessions' => $request->number_of_sessions,
                'image' => $imageName
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        $success_message = "Package was created successfully";
        return response()->json($success_message);
    }

    public function destroy($id)
    {
        $package = Package::find($id);

        Log::debug($package);
        Log::debug(request()->all());

        $message = "Package was not found";
        if (!$package) {
            return response()->json($message);
            Log::debug("if no package");
        }

        Log::debug("before try");
        try {
            Log::debug("start try");
            Log::debug($package->image);
            $package->delete();
            Log::debug("after delete");
            $message = "Package Deleted Successfully";
            return response()->json($message);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(Request $request, $package_id)
    {
        $package = Package::findOrFail($package_id);
        if ($package->image !== null) {
            $imageName = $package->image;
        }

        if ($request->hasFile('image')) {
            if ($imageName != null) {
                unlink(public_path('assets/images/packages/') . $imageName);
            }
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            // dd($ext);
            $imageName = $request->name . uniqid() . ".$ext";
            $image->move(public_path('assets/images/packages'), $imageName);
            // dd($data, $name);
        }

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'number_of_sessions' => $request->number_of_sessions,
            'image' => $imageName
        ]);
        $success_message = "Package was updated successfully";
        return response()->json($success_message);
    }
}
