<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\CandidateSearchController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {


        Route::middleware('role:admin')->group(function () {
            Route::get('/candidates/trashed', [CandidateController::class, 'trashed']);
            Route::delete('/candidates/{id}/force', [CandidateController::class, 'forceDelete']);
            Route::patch('/candidates/{id}/restore', [CandidateController::class, 'restore']);
        });
    });

        Route::apiResource('/candidates', CandidateController::class);
        Route::get('/search/candidates', CandidateSearchController::class);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'getSelfProfile']);




    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [Authcontroller::class, 'register']);
});
