<?php

namespace App\Http\Controllers;

use App\Http\Requests\Session\StoreSessionRequest;
use App\Http\Requests\Sessions\CreateSessionRequest;
use App\Http\Resources\SessionResource;
use App\Http\Traits\ResponseTrait;
use App\Models\Session;


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
            $session->coaches()->sync($request->coaches);
        }catch(\Exception $e){
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        return $this->createResponse(200, $session);
    }

    public function destroy($id){
        if(!Session::find($id)){
            return $this->createResponse(404, [], false, "Session not found");
        }
        try{
            $session = Session::findOrFail($id);
            $users = $session->users;
            if(count($users)){
                return $this->createResponse(200, [], false, "Session was attended by a user; can't delete it");
            }
            $session->update([
              'isDeleted'=>true
            ]);
            return $this->createResponse(200, [], true, "Session deleted successfully");
        }catch (\Exception $e){
            return $this->createResponse(500, [], false, "Server error");
        }
    }

    public function update(CreateSessionRequest $request, $session_id){
        try{
            $session = Session::findOrFail($session_id);
            $session ->update([
                'name'=> $request->name,
                'branch_id'=> $request->branch_id,
                'start_time' => $request->start_time,
                'end_time' =>$request ->end_time,
            ]);
            $session->coaches()->sync($request->coaches);
        }catch(\Exception $e){
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        return $this->createResponse(200, $session);
    }

    public function get_old_sessions()
    {
        $branch_id = request('branch_id');
        $session = Session::where("end_time", "<=", now())->where('branch_id', $branch_id)->get();
        return SessionResource::collection($session);
    }



    public function paginate()
    {

        $sortField = request('sortField', "start_time");
        if (!in_array($sortField, ['name','start_time']))
            $sortField = "start_time";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";

        try {
            $branch_id = request('branch_id');
            $sessions = Session::where("end_time", ">=", now())->where('branch_id',$branch_id)->when(request("search"),function ($q) {
                $q->where(function ($query) {
                    $query->where("name", "like", "%" . request("search") . "%");
                });
            })->orderBy($sortField, $sortDirection);

            $sessions = SessionResource::collection($sessions->paginate(5));
        } catch (\Throwable $th) {
            return $this->createResponse(500, [], false, "server error");
        }

        return [
            "data" => $sessions->response()->getData(true),
            "isSuccess" => true,
            "errors" => [],
            "statusCode" => 200
        ];
    }

    
    public function oldPaginate()
    {

        $sortField = request('sortField', "start_time");
        if (!in_array($sortField, ['name','start_time']))
            $sortField = "start_time";

        $sortDirection = request('sortDirection', "desc");
        if (!in_array($sortDirection, ['asc', 'desc']))
            $sortDirection = "desc";

        try {
            $branch_id = request('branch_id');
            $sessions = Session::where("end_time", "<=", now())->where('branch_id',$branch_id)->when(request("search"),function ($q) {
                $q->where(function ($query) {
                    $query->where("name", "like", "%" . request("search") . "%");
                });
            })->orderBy($sortField, $sortDirection);

            $sessions = SessionResource::collection($sessions->paginate(5));
        } catch (\Throwable $th) {
            return $this->createResponse(500, [], false, "server error");
        }

        return [
            "data" => $sessions->response()->getData(true),
            "isSuccess" => true,
            "errors" => [],
            "statusCode" => 200
        ];
    }

}


