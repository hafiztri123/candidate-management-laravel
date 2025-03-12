<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\CandidateSearchController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [Authcontroller::class, 'register']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Admin-only routes
        Route::middleware('role:admin')->group(function () {
            Route::get('/candidates/trashed', [CandidateController::class, 'trashed']);
            Route::delete('/candidates/{id}/force', [CandidateController::class, 'forceDelete']);
            Route::patch('/candidates/{id}/restore', [CandidateController::class, 'restore']);
        });
        // Regular user routes
        Route::apiResource('/candidates', CandidateController::class);
        Route::get('/search/candidates', CandidateSearchController::class);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'getSelfProfile']);


    });
});