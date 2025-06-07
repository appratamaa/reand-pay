<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataCheckController;

Route::get('/check-latest-data', [DataCheckController::class, 'latestData']);
Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
});


