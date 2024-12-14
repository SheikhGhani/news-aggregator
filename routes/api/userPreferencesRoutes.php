<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserPreferenceController;

Route::middleware('auth:sanctum')->prefix('preferences')->group(function () {
    Route::get('/', [UserPreferenceController::class, 'index']); // Retrieve preferences
    Route::post('/', [UserPreferenceController::class, 'store']); // Set preferences
    Route::get('/news-feed', [UserPreferenceController::class, 'getPersonalizedFeed']); // Personalized news feed
});