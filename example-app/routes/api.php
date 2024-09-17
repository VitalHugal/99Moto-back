<?php

use App\Http\Controllers\UserCoordinatesController;
use App\Http\Controllers\CoordinatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//verificar coordenadas
Route::get("/verificar-coordenadas/{id}", [CoordinatesController::class, "verifyCoordinates"]);

//cadastrar coordenadas users
Route::post("/coordenadas-users", [UserCoordinatesController::class, "coordinatesUsers"]);