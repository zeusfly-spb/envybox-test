<?php

use App\Http\Controllers\PollController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('polls')->group(function () {
    Route::post('/', [PollController::class,'store']);
    Route::get('/{code}', [PollController::class, 'show']);
    Route::post('/{code}/vote', [PollController::class, 'vote']);
});