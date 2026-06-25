<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QueueController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route for Hardware Output (LCD)
Route::get('/queue/active', [QueueController::class, 'activeQueue']);
