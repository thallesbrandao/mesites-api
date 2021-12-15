<?php

use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\SitesController;
use App\Http\Controllers\Api\TemplatesController;
use App\Http\Controllers\Api\UserController;
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

Route::prefix('v1')->group(function () {
    Route::get('emails/{token}/plans', [EmailController::class, 'plan'])->name('email.plans');
    Route::get('templates/{token}', [TemplatesController::class, 'index'])->name('templates');
    Route::put('templates/{token}', [TemplatesController::class, 'update'])->name('templates.update');
    Route::resource('emails/{token}', EmailController::class);    
    Route::resource('sites', SitesController::class);
    Route::resource('user', UserController::class);
});
