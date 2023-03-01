<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\UserDepositController;

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

Route::middleware('auth:api')
    ->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('user', UsersController::class);
Route::post('login', [AuthController::class, 'store'])->name('auth.store');
Route::delete('logout/all', [AuthController::class, 'destroy'])->name('auth.destroy')->middleware('auth.basic:web,username');

Route::group(['middleware' => ['auth:api']], function (Router $router) {
    $router->delete('logout', [AuthController::class, 'delete'])->name('auth.delete');
    $router->apiResource('product', ProductsController::class);
    $router->post('deposit', [UserDepositController::class, 'deposit'])->name('user_deposit.store');
    $router->post('reset', [UserDepositController::class, 'reset'])->name('user_deposit.reset');
    $router->post('buy', [PurchaseController::class, 'store'])->name('purchase.store');
});
