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
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            // dd($ext);
            $imageName = "assets/images/packages/" .  uniqid() . ".$ext";
            $image->move(public_path('assets/images/packages'), $imageName);
            // dd($data, $name);
        } else {
            $imageName = "assets/images/noImageYet.jpg";
        }



        $request->validate([
            'name' => ['required', 'min:3'],
            'price' => ['required'],
        ]);



        try {
            $package = Package::create([
                'name' => $request->name,
                'price' => $request->price,
                'number_of_sessions' => $request->number_of_sessions,
                'image' => $imageName
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        $success_message = "Package was created successfully";
        return response()->json(["result" => $success_message, "package" => $package]);
    }

    public function destroy($id)
    {
        $package = Package::find($id);

        $message = "Package was not found";
        if (!$package) {
            return response()->json($message);
        }

        try {
            $package->delete();
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


            if ($request->hasFile('image')) {
                // if ($imageName != null) {
                //     // unlink(public_path('assets/images/packages/') . $imageName);
                // }
                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                // dd($ext);
                $imageName = uniqid() . ".$ext";
                $image->move(public_path('assets/images/packages'), $imageName);
                // dd($data, $name);
                $imageName = 'assets/images/packages/' . $imageName;
            }
            try {
                $package->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'number_of_sessions' => $request->number_of_sessions,
                    'image' => $imageName
                ]);
                $success_message = "Package was updated successfully";
                return response()->json(["message" => $success_message, "package" => $package]);
            } catch (\Exception $e) {
                return response()->json($e);
            }
        } else {
            try {
                $package->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'number_of_sessions' => $request->number_of_sessions,
                ]);
                $success_message = "Package was updated successfully";
                return response()->json($success_message);
            } catch (\Exception $e) {
                return response()->json($e);
            }
        } //else condition

    } //end of update request
}
