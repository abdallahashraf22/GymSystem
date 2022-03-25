<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GymMangerController extends Controller
{
    public function index()
    {
        $managers = User::where('role', 'branch manager')->with('branch')->get();

        // $managers = User::whereHas('branch')->with('branch')->get();
        return response()->json($managers);
    }
}
