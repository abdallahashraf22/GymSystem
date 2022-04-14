<?php

namespace App\Http\Controllers;

// use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    public function getBranchRevenue()
    {
        $revenue = DB::table('packages_users_branches')
            ->select(DB::raw("date_format(packages_users_branches.created_at, '%m') as month"), DB::raw('sum(enrollement_price) as total'), 'branch_id', 'name')
            ->join('branches', 'packages_users_branches.branch_id', '=', 'branches.id')
            ->groupBy('branch_id', DB::raw("date_format(packages_users_branches.created_at, '%m')"))
            ->get();

        return response()->json($revenue);
    }

    public function getBranches()
    {
        $branches = DB::table('packages_users_branches')
            ->select('branch_id', 'name')
            ->join('branches', 'packages_users_branches.branch_id', '=', 'branches.id')
            ->groupBy('branch_id')
            ->get();

        return response()->json($branches);
    }

    public function getBranchMonthlyRevenue(Request $request)
    {
        $branch_id = $request[0];
        $branchMonthlyRevenue = DB::table('packages_users_branches')
            ->select(DB::raw("date_format(packages_users_branches.created_at, '%m') as month"), DB::raw('sum(enrollement_price) as total'))
            ->WHERE('packages_users_branches.branch_id', '=', $branch_id)
            ->groupBy(DB::raw("date_format(packages_users_branches.created_at, '%m')"))
            ->get();

        return response()->json($branchMonthlyRevenue);
        // return $branch_id;
    }
}
