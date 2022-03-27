<?php

use App\Http\Controllers\AuthController;
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
//Routes that does not require authentication
Route::post('/issue-token', [AuthController::class,'issueToken']);
Route::post('/refresh-token', [AuthController::class,'refreshToken']);
//Protected routes that require authentication
Route::middleware('auth:api')->group(function(){

    Route::post('/test', function(){
        return "Congrates! You are authenticated now.";
    });
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/logout-partial', [AuthController::class,'logoutPartial']);
});
