<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CityManagerController;
use App\Http\Controllers\GymMangerController;
use App\Http\Controllers\PackageController;

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

##### Branches from CityManagerController  ##############
Route::get('/citybranches', [CityManagerController::class, 'getAllBranches']);
Route::post('/createbranch', [CityManagerController::class, 'createBranch']);
Route::post('/editbranch/{branchId}', [CityManagerController::class, 'editBranch']);
Route::delete('/deletebranch/{branchId}', [CityManagerController::class, 'deleteBranch']);
#######################################

##### Managers from CityManagerController  ######
Route::get('/managers', [CityManagerController::class, "indexManagers"]);
Route::post('/managers', [CityManagerController::class, "storeManager"]);
Route::post('/managers/{managerId}', [CityManagerController::class, "updateManager"]);
Route::delete('/managers/{managerId}', [CityManagerController::class, "destroyManager"]);
###########################




##### for JWT Auth ######
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, "login"]);
});
###########################



##### users from UserController ######
Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{user}', [UserController::class, "show"]);
Route::post('/users', [UserController::class, "store"]);
Route::post('/users/{user}', [UserController::class, "update"]);
Route::delete('/users/{user}', [UserController::class, "destroy"]);
###############################


###### CityManagers from UserController ######
Route::get('/citymanagers', [UserController::class, "indexCityManagers"]);
Route::get('/citymanagers/{citymanager}', [UserController::class, "showCityManager"]);
Route::post('/citymanagers', [UserController::class, "storeCityManager"]);
Route::put('/citymanagers/{citymanager}', [UserController::class, "updateCityManager"]);
Route::delete('/citymanagers/{citymanager}', [UserController::class, "destroyCityManager"]);
###############################



#### gym managers routs #####
Route::get('/gymmanagers', [GymMangerController::class, 'index']);
Route::post('/gymmanagers', [GymMangerController::class, "store"]);
Route::get('/gymmanagers/{gymmanager}', [GymMangerController::class, 'show']);
Route::post('/gymmanagers/{gymmanager}', [GymMangerController::class, "update"]);
Route::delete('/gymmanagers/{gymmanager}', [GymMangerController::class, "destroy"]);
#############################


#### Sessions routes #####
Route::get('/sessions', [SessionController::class, 'index']);
Route::post('/sessions', [SessionController::class, 'store']);
Route::get('/sessions/{session}', [SessionController::class, 'show']);
Route::put('/sessions/{session}', [SessionController::class, 'update']);
Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);
###########################



#### packages routes #####
Route::get('/packages', [PackageController::class, 'index']);
Route::post('/packages', [PackageController::class, 'store']);
Route::get('/packages/{package}', [PackageController::class, 'show']);
Route::put('/packages/{package}', [PackageController::class, 'update']);
Route::delete('/packages/{package}', [PackageController::class, 'destroy']);
###########################


#### coaches routes ####
Route::get('/coaches', [CoachController::class, 'index']);
