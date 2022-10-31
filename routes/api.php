<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    VehicleController
};

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

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/logout', [AuthController::class, 'logout']);

    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::post('vehicles', [VehicleController::class, 'store']);
    Route::get('vehicles/sales-reports', [VehicleController::class, 'showSalesReports']);
    Route::get('vehicles/{uuid}/stock', [VehicleController::class, 'showStock']);
    Route::get('vehicles/{uuid}/sales-report', [VehicleController::class, 'showSalesReport']);
    Route::post('vehicles/{uuid}/sale', [VehicleController::class, 'saleVehicle']);
});
