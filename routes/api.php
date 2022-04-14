<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\apiAuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//Route::group([
//    'middleware' => 'api',
//    'prefix' => 'auth'
//], function ($router) {
//    Route::post('login', [AuthController::class, "login"]);
//});
###########################

##### users from UserController ######
Route::get('/users', [UserController::class, "index"]);
Route::get('/users/paginate', [UserController::class, "paginate"]);
Route::get('/users/{user}', [UserController::class, "show"]);
Route::post('/users', [UserController::class, "store"]);
Route::post('/users/{user}', [UserController::class, "update"]);
Route::delete('/users/{user}', [UserController::class, "destroy"]);
###############################

###### CityManagers from UserController ######
Route::apiResource('citymanagers', CityManagerController::class)->except('update');
Route::post('/citymanagers/{citymanager}', [CityManagerController::class, 'update']);
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
Route::post('/packages/{package}', [PackageController::class, 'update']);
Route::delete('/packages/{package}', [PackageController::class, 'destroy']);
###########################


#### coaches routes ####
Route::get('/coaches', [CoachController::class, 'index']);
Route::post('/coaches', [CoachController::class, 'store']);
Route::get('/coaches/{coach}', [CoachController::class, 'show']);
Route::put('/coaches/{coach}', [CoachController::class, 'update']);
Route::delete('/coaches/{coach}', [CoachController::class, 'destroy']);
Route::put('/packages/{package}', [PackageController::class, 'update']);
Route::delete('/packages/{package}', [PackageController::class, 'destroy']);
###########################


######### Cities Routes  ###############
Route::apiResource("cities", CityController::class)->except('update');
Route::post('/cities/{city}', [CityController::class, 'update']);
Route::get('/newcities/', [CityController::class, 'indexNewCities']);

#######################################

########### Show Attendance Table ##############
Route::apiResource('attendance', AttendanceController::class);
################################################


#### Branches routes ####
Route::get('/branches', [BranchController::class, 'index']);
#######################################


####### Authentication ####################
// Register
Route::post('/register', [ApiAuthController::class, 'register'])->name('auth.handleRegister');
// Login
//Route::get('/login', [ApiAuthController::class, 'test'])->name('login');
// Logout
Route::post('/logout', [ApiAuthController::class, 'logout'])->name('auth.logout');

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['auth', 'signed']);

//Route::get('/login/{id}', [ApiAuthController::class, 'test'])->name('login');



