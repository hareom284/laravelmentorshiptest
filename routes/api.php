<?php

use App\Http\Controllers\Api\V1\TourApiController;
use App\Http\Controllers\Api\V1\TravelApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('travels',[TravelApiController::class,'index']);
Route::get('travels/{travel:slug}/tours',[TourApiController::class,'index']);
