<?php

use App\Http\Controllers\UserCoordinatesController;
use App\Http\Controllers\VoucherCoordinatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//verificar coordenadas
Route::get("/verificar-coordenadas/{id}", [VoucherCoordinatesController::class, "verifyCoordinates"]);

//inserir vouchers
Route::post("/insert-vouchers", [VoucherCoordinatesController::class, "insertVoucherCoordinates"]);

//cadastrar coordenadas users
Route::post("/coordenadas-users", [UserCoordinatesController::class, "coordinatesUsers"]);

Route::delete("/delete-users/{id}", [UserCoordinatesController::class, "deleteCoordinatesUsers"]);