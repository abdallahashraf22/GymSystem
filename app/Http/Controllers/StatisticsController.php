<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->except([]);
        $this->middleware("isBranchManager")->except([]);
    }

    public function getRevenue(Request $request)
    {
        $revenue = DB::table('packages_users_branches as pub')
            ->select(
                DB::raw("YEAR(pub.created_at) as year"),
                DB::raw("MONTH(pub.created_at) as month"),
                DB::raw('sum(enrollement_price) as total')
            )
            ->groupBy("month", 'year')
            ->orderBy('month')
            ->join('branches as b', 'pub.branch_id', '=', 'b.id')
            ->join('cities as c', 'c.id', '=', 'b.city_id')
            ->when(request('city_id') != 'all', function ($query) {
                $query->where(function ($q) {
                    $q->where("c.id", request("city_id"));
                });
            })->when(request('branch_id') != 'all', function ($query) {
                $query->where(function ($q) {
                    $q->where("b.id", request("branch_id"));
                });
            })
            ->get();

        return response()->json($revenue);
    }

    public function getTopUsers(Request $request)
    {
        $topUsers = DB::table("packages_users_branches as pub")
            ->select(DB::raw("name"), DB::raw("sum(package_sessions) as 'total_sessions'"))
            ->groupBy("name")
            ->orderByDesc("total_sessions")
            ->join("users as u", "pub.user_id", "=", "u.id")
            // ->join('cities as c', 'c.id', '=', 'b.city_id')
            // ->when(request('city_id') != 'all', function ($query) {
            //     $query->where(function ($q) {
            //         $q->where("c.id", request("city_id"));
            //     });
            // })->when(request('branch_id') != 'all', function ($query) {
            //     $query->where(function ($q) {
            //         $q->where("b.id", request("branch_id"));
            //     });
            // })
            ->get();
        return response()->json($topUsers);
    }
}
