<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Mail\OrderShipped;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use function bcrypt;
use function event;
use function response;

class apiAuthController extends Controller
{
    use ResponseTrait;

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
        event(new Registered($user));
//        Mail::to($request->email)->send(new OrderShipped($user));
        return $this->createResponse(200, $user);

    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $is_Login = Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if (!$is_Login) {
            return response()->json([
                'Error' => 'Credentials are not Correct'
            ]);
        }

        User::where('email', '=', $data['email'])
            ->update(['remember_token' => Str::random(10)]);

        $user = User::where('email', '=', $data['email'])->first();

        return $this->createResponse(200, $user);


    }


    public function logout(Request $request)
    {
        $accessToken = $request->header('Access-Token');
        $user = User::where('remember_token', $accessToken)->first()
            ->update(['remember_token' => null]);
        return $this->createResponse(200, "Success Log Out");;
    }
    public function test(){
        echo "mnkrjg";

    }
}
