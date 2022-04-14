<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getRevenue(Request $request)
    {
        $request['city_id'] = "1";
        $request['branch_id'] = "31 ";


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
                $query->where("c.id", request('city_id'));
            })->when(request('branch_id') != 'all', function ($query) {
                $query->where("b.id", request('branch_id'));
            })
            ->get();

        return response()->json($revenue);
    }
}
