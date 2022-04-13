<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    use ResponseTrait;

    public function index()
    {

    }


    public function store(AttendanceRequest $request)
    {
        $session = Session::find($request->session_id);
        $user = User::find($request->user_id);
        if($session && $user){
            $session->users()->save($user);
            return $this->createResponse(200, [], true, "User attended session successfully");
        }
        return $this->createResponse(404, [], false, "User or Session doesn't exist");
    }


    public function show($branchId)
    {
        $AttendanceSheet = DB::table('user_session')
            ->join('users', 'user_session.user_id', '=', 'users.id')
            ->join('sessions', 'user_session.session_id', '=', 'sessions.id')
            ->select('users.name as UserName', 'sessions.name as SessionName', 'user_session.updated_at as AttendanceTime')
            ->where('sessions.branch_id', '=', $branchId)
            ->get();
        return response()->json($AttendanceSheet);
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
