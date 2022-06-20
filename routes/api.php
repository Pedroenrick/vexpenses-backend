<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/", function () {
    return response()->json([
        "message" => "Welcome to the API"
    ]);
});

Route::middleware('jwt.auth')->group(function () {
    Route::apiResource("addresses", AddressController::class);
    Route::apiResource("contacts", ContactController::class);
    Route::apiResource("phones", PhoneController::class);
    Route::apiResource("categories", CategoryController::class);

    Route::post("addresses/getAddress", [AddressController::class, "getAddressByCep"]);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
