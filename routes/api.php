<?php

use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;

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

Route::post('/register', [AuthenticateController::class, 'register']);
Route::post('/login', [AuthenticateController::class, 'login']);

/*Restaurants*/

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/restaurants', [RestaurantController::class, 'store']);
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']);
});

/*Categories*/

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

/*Dishes*/

Route::get('/dishes', [DishController::class, 'index']);
Route::get('/dishes/{dish}', [DishController::class, 'show']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/dishes', [DishController::class, 'store']);
    Route::put('/dishes/{dish}', [DishController::class, 'update']);
    Route::delete('/dishes/{dish}', [DishController::class, 'destroy']);
});

/*Tables*/

Route::get('/tables', [TableController::class, 'index']);
Route::get('/tables/{table}', [TableController::class, 'show']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/tables', [TableController::class, 'store']);
    Route::put('/tables/{table}', [TableController::class, 'update']);
    Route::delete('/tables/{table}', [TableController::class, 'destroy']);
});

/*Requests for booking*/

Route::get('/requests', [BookingRequestController::class, 'index']);
Route::get('/requests/{request}', [BookingRequestController::class, 'show']);
Route::post('/requests', [BookingRequestController::class, 'store']);
Route::put('/requests/{request}', [BookingRequestController::class, 'update']);
Route::delete('/requests/{request}', [BookingRequestController::class, 'destroy']);

/*Reservations*/

/*Route::get('/reservations', [ReservationController::class, 'index']);
  Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
  Route::post('/reservations', [ReservationController::class, 'store']);

  Route::middleware('auth:sanctum')->group(function () {
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
});*/


/*Orders*/

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{order}', [OrderController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
});

/*Payments*/

Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payments', [PaymentController::class, 'store']);

Route::get('/success', [PaymentController::class, 'success']);
Route::get('/cancel', [PaymentController::class, 'cancel']);




