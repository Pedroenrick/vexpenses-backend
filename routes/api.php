<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PhoneController;
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

Route::apiResource("address" , AddressController::class);
Route::apiResource("contact" , ContactController::class);
Route::apiResource("phone" , PhoneController::class);
