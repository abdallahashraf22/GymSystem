<?php
namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CityManager;
use App\Http\Controllers\GymMangerController;

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
##### City-Branches ##############
Route::get('/citybranches', [BranchController::class, 'getAllBranches']);
Route::post('/createbranch', [BranchController::class, 'createBranch']);
Route::post('/editbranch/{branchId}', [BranchController::class, 'editBranch']);
Route::delete('/deletebranch/{branchId}', [BranchController::class, 'deleteBranch']);
#######################################

##### Managers from User Controller ######
Route::get('/managers', [UserController::class, "indexManagers"]);
Route::post('/managers', [UserController::class, "storeManager"]);
Route::post('/managers/{managerId}', [UserController::class, "updateManager"]);
Route::delete('/managers/{managerId}', [UserController::class, "destroyManager"]);

###########################

##### for JWT Auth ######
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, "login"]);
});
###########################



##### for user Controller ######
Route::get('/users', [UserController::class, "index"]);
Route::post('/users', [UserController::class, "store"]);
Route::post('/users/{user}', [UserController::class, "update"]);
Route::delete('/users/{user}', [UserController::class, "destroy"]);

###########################





#### gym managers routs #####
Route::get('/gymmanagers', [GymMangerController::class, 'index']);
Route::post('/gymmanagers', [GymMangerController::class, "store"]);
Route::post('/gymmanagers/{gymmanager}', [GymMangerController::class, "update"]);
Route::delete('/gymmanagers/{gymmanager}', [GymMangerController::class, "destroy"]);
###########################


#### Sessions routes #####
Route::get('/sessions', [SessionController::class, 'index']);
Route::post('/sessions', [SessionController::class, 'store']);
Route::get('/sessions/{session}', [SessionController::class, 'show']);
Route::put('/sessions/{session}', [SessionController::class, 'update']);
Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);
###########################
