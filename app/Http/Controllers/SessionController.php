<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Http\Resources\CoachResource;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(){
        $session = Session::get();
        return SessionResource::collection($session);
    }

    public function show($id){
        $session = Session::with('coaches')->findOrFail($id);
        return new SessionResource($session);
    }

    public function store(Request $request){
        try{
            $session = Session::create([
                'name' => $request->name,
                'branch_id'=> $request->branch_id,
                'start_time' => $request->start_time,
                'end_time' =>$request ->end_time,
            ]);
            $session->coaches()->sync($request->coaches);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
        $success_message = "Session was created successfully";
        return response()->json($success_message);
    }

    public function destroy($id){
        $message = "Session was not found";
        if(!Session::find($id)){
            return response()->json($message);
        }
        try{
            $session = Session::findOrFail($id);
            $session->delete($id);
            $message = "Deleted Successfully";
            return response()->json($message);
        }catch (\Exception $e){
            return response()->json($e);
        }
    }

    public function update(Request $request, $session_id){
        $session = Session::findOrFail($session_id);

        $session ->update([
            'name'=> $request->name,
            'branch_id'=> $request->branch_id,
            'start_time' => $request->start_time,
            'end_time' =>$request ->end_time,
        ]);
        $session->coaches()->sync(explode(",", $request->coaches));
        $success_message = "Session was updated successfully";
        return response()->json($success_message);
    }

}
