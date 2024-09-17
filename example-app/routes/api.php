<?php

use App\Http\Controllers\CoordinatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/verificar-coordenadas", [CoordinatesController::class, "verifyCoordinates"]);