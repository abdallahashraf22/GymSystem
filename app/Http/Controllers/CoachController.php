<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoachResource;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index(){
        $coach = Coach::get();
        return CoachResource::collection($coach);
        return response()->json($coach);
    }

    public function show($id){
        $coach = Coach::find($id);
        return response()->json($coach);
    }

    public function store(Request $request){
        try{
            Coach::create([
                'name' => $request->name,
            ]);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
        $success_message = "Coach was created successfully";
        return response()->json($success_message);
    }

    public function destroy($id){
        $message = "Coach was not found";
        if(!Coach::find($id)){
            return response()->json($message);
        }
        try{
            $coach = Coach::findOrFail($id);
            $coach->delete($id);
            $message = "Deleted Successfully";
            return response()->json($message);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    public function update(Request $request, $id){
        $coach = Coach::findOrFail($id);
        $coach ->update([
            'name'=> $request->name,
        ]);
        $success_message = "Coach was updated successfully";
        return response()->json($success_message);
    }
}
