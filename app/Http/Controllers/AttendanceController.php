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

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('isBranchManager')->except(["store"]);
    }

    public function index()
    {
    }


    public function store(AttendanceRequest $request)
    {
        $session = Session::find($request->session_id);
        $user = User::find($request->user_id);
        if ($session && $user) {
            $branch = $session->branch()->first();
            $remaining_session = DB::table('packages_users_branches')
                ->where("user_id", $request->user_id)
                ->where("branch_id", $branch->id)
                ->where("isDeleted", 0)
                ->get()->first();
            if ($remaining_session) {
                if ($remaining_session->remianing_sessions == 1) {
                    DB::table('packages_users_branches')->where("id", $remaining_session->id)->update([
                        'remianing_sessions' => $remaining_session->remianing_sessions - 1,
                        'isDeleted' => 1
                    ]);
                } else {
                    DB::table('packages_users_branches')->where("id", $remaining_session->id)->update([
                        'remianing_sessions' => $remaining_session->remianing_sessions - 1
                    ]);
                }
            } else {
                return $this->createResponse(200, [], false, "ops... sorry, no remaining sessions for you, please buy a new package first");
            }
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
        return $this->createResponse(200, $AttendanceSheet);
    }

    public function getUserAttendance($userId)
    {
        $AttendanceSheet = DB::table('user_session')
            ->join('users', 'user_session.user_id', '=', 'users.id')
            ->join('sessions', 'user_session.session_id', '=', 'sessions.id')
            ->select('users.name as UserName', 'sessions.name as SessionName', 'user_session.updated_at as AttendanceTime')
            ->where('user_session.user_id', '=', $userId)
            ->get();
        return $this->createResponse(200, $AttendanceSheet);
    }
}
