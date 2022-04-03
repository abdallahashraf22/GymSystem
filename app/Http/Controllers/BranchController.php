<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }




    public function show($id)
    {
        $branches = Branch::where("city_id", $id)->get();
        return response()->json($branches);
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
