<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->middleware('api')
    ->group(function () {
        require base_path('routes/api.php');
    });

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api\/).*$');
