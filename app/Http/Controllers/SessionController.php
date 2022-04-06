<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckBranchId;
use App\Http\Requests\Session\StoreSessionRequest;
use App\Http\Requests\Sessions\CreateSessionRequest;
use App\Http\Resources\SessionResource;
use App\Http\Resources\CoachResource;
use App\Http\Traits\ResponseTrait;
use App\Models\Session;
use App\Rules\SessionOverlap;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    use ResponseTrait;

    public function __construct()
    {
//        $this->middleware(CheckBranchId::class);
    }

    public function index(){
        $branch_id = request('branch_id');
        $session = Session::where("end_time", ">=", now())->where('branch_id', $branch_id)->get();
        return SessionResource::collection($session);
    }

    public function show($id){
        $session = Session::with('coaches')->findOrFail($id);
        return new SessionResource($session);
    }

    public function store(CreateSessionRequest $request){
        try{
            $session = Session::create([
                'name' => $request->name,
                'branch_id' => $request->branch_id,
                'start_time' => $request->start_time,
                'end_time' => $request ->end_time,
            ]);
            $session->coaches()->syncWithoutDetaching($request->coaches);
        }catch(\Exception $e){
            return $this->createResponse(200, [], false, $e->getMessage());
        }
        return $this->createResponse(200, $session);
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
        $request->validate(
            [
                'start_time'=>new SessionOverlap(),
            ]
        );

        $session ->update([
            'name'=> $request->name,
            'branch_id'=> $request->branch_id,
            'start_time' => $request->start_time,
            'end_time' =>$request ->end_time,
        ]);
        $session->coaches()->syncWithoutDetaching($request->coaches);
        $success_message = "Session was updated successfully";
        return response()->json($success_message);
    }

    public function get_old_sessions()
    {
        $branch_id = request('branch_id');
        $session = Session::where("end_time", "<=", now())->where('branch_id', $branch_id)->get();
        return SessionResource::collection($session);
    }

}
