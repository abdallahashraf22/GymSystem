<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityManager;
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

Route::get('/citybranches', [CityManager::class, 'getAllBranches']);
Route::post('/createbranch', [CityManager::class, 'createBranch']);
Route::post('/editbranch/{branchId}', [CityManager::class, 'editBranch']);
Route::delete('/deletebranch/{branchId}', [CityManager::class, 'deleteBranch']);

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
Route::get('/users/{user}', [UserController::class, "show"]);
Route::post('/users', [UserController::class, "store"]);
Route::post('/users/{user}', [UserController::class, "update"]);
Route::delete('/users/{user}', [UserController::class, "destroy"]);
###############################
