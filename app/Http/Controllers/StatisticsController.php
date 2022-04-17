<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    use ResponseTrait;

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
        try {
            $topUsers = DB::table("packages_users_branches as pub")
                ->join("users as u", "pub.user_id", "=", "u.id")
                ->join('branches as b', 'pub.branch_id', '=', 'b.id')
                ->join('cities as c', 'c.id', '=', 'b.city_id')
                ->groupBy("pub.user_id", "u.name")
                ->when(request('city_id') != 'all', function ($query) {
                    $query->where(function ($q) {
                        $q->where("c.id", request("city_id"));
                    });
                })->when(request('branch_id') != 'all', function ($query) {
                    $query->where(function ($q) {
                        $q->where("b.id", request("branch_id"));
                    });
                })
                ->select("pub.user_id", "u.name", DB::raw("SUM(pub.enrollement_price) as totoal_price"), DB::raw("SUM(pub.package_sessions) as totoal_sessions"))
                ->orderByDesc(DB::raw("SUM(pub.enrollement_price)"))->limit(10)->get();
        } catch (Exception $e) {
            return $this->createResponse(200, [], false, "server error");
        }

        return $this->createResponse(200, $topUsers);
    }
}
