<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    public function index()
    {

    }


    public function store(Request $request)
    {

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
