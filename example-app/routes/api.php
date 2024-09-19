<?php

use App\Http\Controllers\UserCoordinatesController;
use App\Http\Controllers\VoucherCoordinatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//pegar os voucher proximos a você
Route::get("/verificar-coordenadas/{id}", [VoucherCoordinatesController::class, "verifyCoordinates"]);

//inserir vouchers
Route::post("/insert-vouchers", [VoucherCoordinatesController::class, "insertVoucherCoordinates"]);

//pegar voucher e excluir para não ser possível utiliza-lo novamente
Route::delete("/user-get-voucher/{id}", [VoucherCoordinatesController::class, "userGetVoucher"]);

// -------------------------------------------------

//cadastrar coordenadas users
Route::post("/coordenadas-users", [UserCoordinatesController::class, "coordinatesUsers"]);

//deletar coordenadas user
Route::delete("/delete-users/{id}", [UserCoordinatesController::class, "deleteCoordinatesUsers"]);