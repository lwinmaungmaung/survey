<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/form/{form:slug}', [FormController::class,'show'])->name('public.form.show');
Route::post('/form/{form:slug}', [FormController::class,'public_submit'])->name('public.form.submit');
