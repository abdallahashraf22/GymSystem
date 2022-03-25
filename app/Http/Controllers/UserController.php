<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class UserController extends Controller
{

    public function index()
    {
        $users = User::where("role", "user")->get();
        return response()->json($users);
    }

    public function show(int $id)
    {
        $user = User::find($id);
        return response()->json($user);
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
}
