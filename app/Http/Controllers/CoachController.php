<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index(){
        $coach = Coach::get();
        return response()->json($coach);
    }

    public function show($id){
        $coach = Coach::find($id);
        return response()->json($coach);
    }
}
