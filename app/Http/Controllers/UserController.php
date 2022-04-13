<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseTrait, UploadImageTrait;

    public function __construct()
    {
    }

    # Normal Users
    public function index()
    {
        try {
            $users = User::where("role", "user")->get();
        } catch (\Exception $e) {

            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $users);
    }

    public function getSomeByEmail()
    {
        try {
            $users = User::where("role", "user")->when(request("search"), function ($q) {
                $q->where(function ($query) {
                    $query->where("email", "like", "%" . request("search") . "%");
                });
            })->limit(5)->get(['id', 'email']);
        } catch (\Exception $e) {

            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $users);
    }

    public function paginate()
    {
        $sortField = request('sortField', "created_at");
        if (!in_array($sortField, ['name', 'email', 'created_at']))
            $sortField = "created_at";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";

        try {
            $users = User::where("role", "user")->when(request("search"), function ($q) {
                $q->where(function ($query) {
                    $query->where("name", "like", "%" . request("search") . "%")
                        ->orWhere("email", "like", "%" . request("search") . "%");
                });
            })->orderBy($sortField, $sortDirection)->paginate(5);
        } catch (\Throwable $th) {
            return $this->createResponse(500, [], false, "server error");
        }

        return $this->createResponse(200, $users);
    }

    public function show(int $id)
    {
        try {
            $user = User::find($id);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, $user);
    }

    public function store(CreateUserRequest $request)
    {
        $imageName = $this->uploadImage("users", $request->file('image'));
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'isbanned' => false,
                'password' => bcrypt($request->password),
                'national_id' => $request->national_id,
                'role' => "user",
                'image_url' => $imageName,
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(200, [], false, "server error");
        }

        return $this->createResponse(200, $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $imageName = $this->uploadImage("users", $request->file('image'));
        $user->name = $request->name;
        $user->email = $request->email;
        $user->national_id = $request->national_id;
        $user->image_url = $imageName;
        logger($imageName);
        try {
            $user->save();
        } catch (\Exception $e) {
            return $this->createResponse(200, [], false, $e->getMessage());
        }

        return $this->createResponse(200, $user);
    }

    public function destroy(int $id)
    {

        if (!$user = User::find($id))
            return "not found";
        try {
            $user->isDeleted = true;
            $user->save();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, "server error");
        }
        return $this->createResponse(200, "deleted successfully");
    }
}
