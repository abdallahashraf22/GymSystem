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
Route::get('/users/paginate', [UserController::class, "paginate"]);
Route::get('/users/paginate/email', [UserController::class, "getSomeByEmail"]);
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
Route::get('/gymmanagers/paginate', [GymMangerController::class, 'paginate']);
Route::post('/gymmanagers', [GymMangerController::class, "store"]);
Route::get('/gymmanagers/{gymmanager}', [GymMangerController::class, 'show']);
Route::post('/gymmanagers/{gymmanager}', [GymMangerController::class, "update"]);
Route::delete('/gymmanagers/{gymmanager}', [GymMangerController::class, "destroy"]);
#############################


#### Sessions routes #####
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/sessions/paginate', [SessionController::class, 'paginate']);
Route::get('/oldsessions', [SessionController::class, 'get_old_sessions']);
Route::get('/oldsessions/paginate', [SessionController::class, 'oldPaginate']);
Route::post('/sessions', [SessionController::class, 'store']);
Route::get('/sessions/{session}', [SessionController::class, 'show']);
Route::put('/sessions/{session}', [SessionController::class, 'update']);
Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);
###########################
Route::post('/attend', [AttendanceController::class, 'store']);
###########################


#### packages routes #####
Route::get('/packages', [PackageController::class, 'index']);
Route::post('/packages', [PackageController::class, 'store']);
Route::post('/packages/buyToUser', [PackageController::class, 'buyToUser']);
Route::get('/packages/{package}', [PackageController::class, 'show']);
// Route::post('/packages/subscribe', [PackageController::class, 'subscribe']);
Route::post('/packages/{package}', [PackageController::class, 'update']);
Route::delete('/packages/{package}', [PackageController::class, 'destroy']);

###########################


#### coaches routes ####
Route::get('/coaches', [CoachController::class, 'index']);
Route::get('/coaches/paginate', [CoachController::class, 'paginate']);
Route::post('/coaches', [CoachController::class, 'store']);
Route::get('/coaches/{coach}', [CoachController::class, 'show']);
Route::post('/coaches/{coach}', [CoachController::class, 'update']);
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
Route::get('/userattendance/{id}', [AttendanceController::class, 'getUserAttendance']);
################################################


########### Branches routes ########
Route::group([
    'middleware' => 'auth:api',
], function ($router) {
    Route::get('/branches', [BranchController::class, 'index']);
    Route::get('/branches/paginate', [BranchController::class, "paginate"]);
    Route::get('/branches/{branch}', [BranchController::class, 'show']);
    Route::post('/branches', [BranchController::class, "store"]);
    Route::post('/branches/{branch}', [BranchController::class, "update"]);
    Route::delete('/branches/{branch}', [BranchController::class, "destroy"]);
});
#######################################


####### Authentication ####################
// Register
Route::post('/register', [AuthController::class, 'register'])->name('auth.handleRegister');
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
// Verification
Route::post('email/verification-notification', [AuthController::class, 'sendVerificationEmail']);

Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);
//Route::get('/sendnotification', [AuthController::class, 'sendGreetNotification']);



// #### dashboard routes ####
// // Route::get('/dashboard/revenue/branch', [DashBoardController::class, 'getBranchRevenue']);
// Route::get('/dashboard/branches', [DashBoardController::class, 'getBranches']);
// Route::post('/dashboard/branches/monthly', [DashBoardController::class, 'getBranchMonthlyRevenue']);

// ###########################


#### statistics routes ####
Route::get('/statistics/revenue', [StatisticsController::class, 'getRevenue']);
Route::get('/statistics/top-users', [StatisticsController::class, 'getTopUsers']);


###########################
