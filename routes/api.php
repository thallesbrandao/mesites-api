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

    Route::get('emails/{token}/{email}', [EmailController::class, 'show'])->name('email.show');
    Route::get('emails/{token}', [EmailController::class, 'index'])->name('email.index');
    Route::post('emails/{token}', [EmailController::class, 'store'])->name('email.store');
    Route::put('emails/{token}/editar/{email}', [EmailController::class, 'update'])->name('email.update');
    Route::delete('emails/{token}/deletar/{email}', [EmailController::class, 'destroy'])->name('email.destroy');

    Route::resource('sites', SitesController::class);
    Route::resource('user', UserController::class);
});
