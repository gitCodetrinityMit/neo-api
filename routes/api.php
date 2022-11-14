<?php

use App\Http\Controllers\AuthController;
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


/*
|--------------------------------------------------------------------------
| Auth Route API
|--------------------------------------------------------------------------
*/
Route::get('/user-list', [AuthController::class, 'userList'])->name('users');
Route::post('/signup-user', [AuthController::class, 'signupUser'])->name('user.signup');
Route::post('/signin-user',[AuthController::class, 'signinUser'])->name('user.signin');
Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->name('user.forgotpassword');


/*
|--------------------------------------------------------------------------
| Product Route API
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Category Route API
|--------------------------------------------------------------------------
*/