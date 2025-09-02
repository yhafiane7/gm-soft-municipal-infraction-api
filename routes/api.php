<?php

use Illuminate\Http\Request;

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViolantController;

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

Route::get('/test', function (Request $request) {
    return  'test';
});



Route::resource('/agent', AgentController::class);
Route::resource('/categorie', CategorieController::class);
Route::resource('/commune', CommuneController::class);
Route::resource('/decision', DecisionController::class);
Route::resource('/infraction', InfractionController::class);
Route::resource('/user', UserController::class);
Route::resource('violant', ViolantController::class);
