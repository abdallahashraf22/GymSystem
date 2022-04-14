<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->except(["login", "sendVerificationEmail", "verify", "register"]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'national_id' => 'required',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'isbanned' => false,
                'password' => bcrypt($request->password),
                'national_id' => $request->national_id,
                'role' => "user",
                'remember_token' => Str::random(10),
                'image_url' => "ImageURL",
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        $user->sendEmailVerificationNotification();
//        event(new Registered($user));
//        Mail::to($request->email)->send(new OrderShipped($user));
        return $this->createResponse(200, $user);

    }


    public function login()
    {

        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }


    public function verify(Request $request)
    {
        $user = User::find($request["id"]);
        if ($user->hasVerifiedEmail()) {
            $message ="This Email has already been Verified";
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $message ="You Have Verified your Email Successfully";
        }

        return view("Verify", [ "message" => $message ]);
//        return [
//            'message' => 'Email has been verified'
//        ];
    }

}
