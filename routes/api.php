<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Example endpoint: List of civilizations
Route::get('/civilizations', function () {
    return response()->json([
        ['id' => 1, 'name' => 'Ancient Rome'],
        ['id' => 2, 'name' => 'Ancient Egypt'],
    ]);
});

// Example endpoint: List of leaders
Route::get('/leaders', function () {
    return response()->json([
        ['id' => 1, 'name' => 'Julius Caesar'],
        ['id' => 2, 'name' => 'Cleopatra'],
    ]);
});
