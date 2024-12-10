<?php

use App\Http\Controllers\Api\AuthController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// call all the api routes
foreach (glob(__DIR__.'/api/*.php') as $filename) {
    require  $filename;
}
