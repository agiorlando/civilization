<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CivilizationController;
use App\Http\Controllers\Api\LeaderController;

/*
|--------------------------------------------------------------------------
| API Routes for InvoCivilizations
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are automatically loaded by the framework and assigned the "api"
| middleware group. Enjoy building your API!
|
*/

Route::apiResource('civilizations', CivilizationController::class);
Route::apiResource('leaders', LeaderController::class);
