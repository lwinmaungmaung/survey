<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\UserLogin;
use App\Http\Controllers\UserRegister;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('start')->get('/',static function(){
    return response()->json(['current_date' => now()->toDateString()]);
});

Route::name('user.register')->post('/register', UserRegister::class);
Route::name('user.login')->post('/login', UserLogin::class);

Route::middleware('auth:sanctum')->group(static function(){
    Route::name('user.profile')->get('/user', static function(Request $request){
        return new UserResource($request->user());
    });
    Route::apiResource('form', FormController::class);
});
