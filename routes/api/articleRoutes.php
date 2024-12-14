<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [ArticleController::class, 'show'])->middleware('auth:sanctum');
});