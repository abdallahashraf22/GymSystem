<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Notifications\GreetVerifiedUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->except(["login", "sendVerificationEmail", "verify", "register", "sendGreetNotification"]);
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
        return $this->createResponse(200, $user);
    }


    public function login()
    {

        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->createResponse(200, [], false, ["message" => "email or password are in correct"]);
        }
        $user = User::where('email', request('email'))->get()[0];

        $user->update([
            'last_login' => now()
        ]);
        return $this->createResponse(200, $this->respondWithToken($token));
    }


    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }


    public function verify(Request $request)
    {
        $user = User::find($request["id"]);
        if ($user->hasVerifiedEmail()) {
            $message = "This Email has already been Verified";
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $message = "You Have Verified your Email Successfully";
            $user->notify(new GreetVerifiedUser());
        }

        return view("Verify", ["message" => $message]);
        //        return [
        //            'message' => 'Email has been verified'
        //        ];
    }

    public function sendGreetNotification()
    {
        $welcomeUsers = User::whereNotNull('email_verified_at')->get();
        foreach ($welcomeUsers as $welcomeUser) {
            $welcomeUser->notify(new GreetVerifiedUser());
        }
    }
}
