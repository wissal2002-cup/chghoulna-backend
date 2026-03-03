<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
//use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/






Route::middleware(['auth:sanctum','verified'])->get('/user', function (Request $request) {
    return $request->user();
});

//Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Routes proteges (utilisateur connecte)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/users/{id}', [AuthController::class, 'show']);
Route::middleware('auth:sanctum')->put('/users/{id}', [AuthController::class, 'update']);
Route::apiResource('services', ServiceController::class);
Route::apiResource('reservations', ReservationController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/services',[ServiceController::class,'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/services/{service}', [ServiceController::class, 'update']);
});


Route::middleware('auth:sanctum')->post('/reviews', [ReviewController::class, 'store']);
Route::get('/reviews', [ReviewController::class, 'index']);

Route::middleware('auth:sanctum')->post('/messages', [MessageController::class, 'store']);


//Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'me']);

//payement
Route::post('/payment/checkout', [PaymentController::class, 'checkout']);
Route::post('/contact', [ContactController::class, 'send']);

Route::middleware('auth:sanctum')->get('/users/{id}', [UserController::class, 'show']);
