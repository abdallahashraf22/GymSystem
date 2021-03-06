<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\UserPackage;
use App\Http\Resources\SessionResource;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{

    use UploadImageTrait;
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isBranchManager');
    }

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
        // if ($request->file('image') != null) {
        //     $image = $request->file('image');
        //     $ext = $image->getClientOriginalExtension();
        //     // dd($ext);
        //     $imageName = "assets/images/packages/" .  uniqid() . ".$ext";
        //     $image->move(public_path('assets/images/packages'), $imageName);
        //     // dd($data, $name);
        // } else {
        //     $imageName = "assets/images/noImageYet.jpg";
        // }

        $imageName = $this->uploadImage("packages", $request->file('image'));




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
            // return response()->json($e->getMessage());
            return $this->createResponse(500, [], false, $e);
        }
        $success_message = "Package was created successfully";
        // return response()->json(["result" => $success_message, "package" => $package]);
        return $this->createResponse(201, ["result" => $success_message, "package" => $package]);
    }

    public function destroy($id)
    {
        $package = Package::find($id);

        $message = "Package was not found";
        if (!$package) {
            return response()->json($message);
        }

        try {
            // $package->delete();
            $package->isDeleted = true;
            $package->save();
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
                // $image = $request->file('image');
                // $ext = $image->getClientOriginalExtension();
                // // dd($ext);
                // $imageName = uniqid() . ".$ext";
                // $image->move(public_path('assets/images/packages'), $imageName);
                // // dd($data, $name);
                // $imageName = 'assets/images/packages/' . $imageName;
                $imageName = $this->uploadImage("packages", $request->file('image'));
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

    public function buyToUser(Request $request)
    {
        try {
            $package = Package::find(request('package_id'));
            $price = $package->price * 100;
            $user = User::find(request('user_id'));
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }

        try {
            $payment = $user->charge(
                $price,
                request('paymentMethodId')
            );
            // $payment = $payment->asStripePaymentIntent();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }

        try {
            $transaction = UserPackage::create([
                'package_id' => $request->package_id,
                'user_id' => $request->user_id,
                'branch_id' => $request->branch_id,
                'enrollement_price' => $package->price,
                'remianing_sessions' => $package->number_of_sessions,
                'package_sessions' => $package->number_of_sessions
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        $success_message = "transaction completed successfully";

        return $this->createResponse(201, ["result" => $success_message, "transaction" => "done"]);
    } //end of buy to user
}
