<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('user-profile', [AuthController::class, 'userProfile']);

});

Route::group(['prefix' => 'inventory', 'middleware' => 'admin'], function () {
    Route::post('store', [InventoryController::class, 'store']);
    Route::post('/{id}/update', [InventoryController::class, 'update']);
    Route::post('/readall', [InventoryController::class, 'readAll']);
    Route::post('/{id}/read', [InventoryController::class, 'read']);
    Route::post('/{id}/destroy', [InventoryController::class, 'delete']);
});

Route::group(['prefix' => 'user/inventory'], function () {
    Route::post('/readall', [UserController::class, 'readAll']);
    Route::post('/{id}/read', [UserController::class, 'read']);
});

Route::group(['prefix' => 'cart'], function () {
    Route::post('{inventoryid}/add-to-cart', [CartController::class, 'addInventoryToCart']);
});
