<?php

namespace App\Http\Controllers;

use App\Http\Requests\City\CreateRequest;
use App\Http\Requests\City\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CityController extends Controller
{
    use ResponseTrait, UploadImageTrait;

    public function index()
    {
        $cities = DB::table('cities')
            ->leftJoin('users', 'cities.manager_id', '=', 'users.id')
            ->select('cities.*', 'users.name as UserName')
            ->get();
        return $this->createResponse(200, $cities);
    }

    public function indexNewCities()
    {
        $newCities = DB::table('cities')
            ->where('manager_id', '=', null)
            ->get();
        return $this->createResponse(200, $newCities);
    }


    public function store(createRequest $request)
    {
        $imageName = $this->uploadImage("cities", $request->file('image'));
        try {
            $city = City::create([
                'name' => $request->name,
                'image_url' => $imageName
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        return $this->createResponse(200, $city);
    }


    public function show($cityId)
    {
        $city = City::findOrFail($cityId);
        return $this->createResponse(200, $city);
    }


    public function update(UpdateRequest $request, $cityId)
    {
        try {
            $city = City::findOrFail($cityId);
            $city->update([
                "name" => $request->name,
            ]);
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        return $this->createResponse(200, $city);
    }


    public function destroy($id)
    {
        if (!$city = City::find($id))
            return "not found";
        try {
            $city->delete();
        } catch (\Exception $e) {
            return $this->createResponse(500, [], false, $e->getMessage());
        }
        return $this->createResponse(200, "Deleted Successfully");
    }
}
