<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('users', [App\Http\Controllers\API\UserController::class, 'store']);
        Route::post('login', [App\Http\Controllers\API\SessionController::class, 'store']);
        Route::post('forgot-password', App\Http\Controllers\API\ForgotPasswordController::class);
        Route::post('reset-password', App\Http\Controllers\API\ResetPasswordController::class);

        Route::middleware('auth:sanctum')->group(function () {
            Route::delete('logout', [App\Http\Controllers\API\SessionController::class, 'destroy']);
            Route::get('profile', [App\Http\Controllers\API\ProfileController::class, 'show']);
            Route::put('profile', [App\Http\Controllers\API\ProfileController::class, 'update']);
            Route::post('change-password', App\Http\Controllers\API\ChangePasswordController::class);
        });
    });

    Route::middleware(['app'])->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::put('accounts/{id}/block', [App\Http\Controllers\API\AccountController::class, 'block']);
            Route::get('accounts/{id}/cards', [App\Http\Controllers\API\CardController::class, 'index']);
            Route::put('cards/block', [App\Http\Controllers\API\CardController::class, 'block']);
            Route::get('accounts/{id}/order-items', [App\Http\Controllers\API\OrderItemsController::class, 'index']);
            Route::get('accounts/{id}/credit-purchases', [App\Http\Controllers\API\CreditPurchasesController::class, 'index']);
            Route::post('accounts/{id}/credit-purchases', [App\Http\Controllers\API\CreditPurchasesController::class, 'store']);
            Route::apiResource('accounts', App\Http\Controllers\API\AccountController::class)->only(['show', 'update']);
            Route::get('accounts/{id}/limited-products', [App\Http\Controllers\API\LimitedProductController::class, 'index']);
            Route::put('accounts/{id}/limited-products', [App\Http\Controllers\API\LimitedProductController::class, 'update']);
            Route::post('accounts/{id}/orders', [App\Http\Controllers\API\OrderController::class, 'store']);
            Route::get('accounts/{id}/orders', [App\Http\Controllers\API\OrderController::class, 'index']);
            Route::put('orders/{id}', [App\Http\Controllers\API\OrderController::class, 'update']);
            Route::delete('orders/{id}', [App\Http\Controllers\API\OrderController::class, 'destroy']);
        });

        Route::apiResource('sections', App\Http\Controllers\API\SectionController::class)->only(['index', 'show']);
        Route::get('accounts/{id}/products', [App\Http\Controllers\API\ProductController::class, 'index']);

        Route::apiResource('leads', App\Http\Controllers\API\LeadController::class)->only(['store']);
        Route::apiResource('settings', App\Http\Controllers\API\SettingsController::class)->only(['index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('dependents/{id}/create-user', [App\Http\Controllers\API\DependentController::class, 'createUser']);
        Route::get('dependents/{id}', [App\Http\Controllers\API\DependentController::class, 'show']);
        Route::apiResource('clients.dependents', App\Http\Controllers\API\DependentController::class)->except(['show', 'destroy']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('clients/{id}', [App\Http\Controllers\API\ClientController::class, 'destroy']);
        Route::delete('dependents/{id}', [App\Http\Controllers\API\DependentController::class, 'destroy']);
        Route::delete('accounts/{id}', [App\Http\Controllers\API\AccountController::class, 'destroy']);
        Route::delete('cards/{id}', [App\Http\Controllers\API\CardController::class, 'destroy']);
    });

    Route::get('get-person-by-nif', App\Http\Controllers\API\GetPersonByNifController::class);
    Route::get('people', [App\Http\Controllers\API\PersonController::class, 'search']);
    Route::get('ncms', [App\Http\Controllers\API\NcmController::class, 'search']);


    Route::get('stocks', [App\Http\Controllers\API\StockController::class, 'index']);
    Route::apiResource('measurement-units', App\Http\Controllers\API\MeasurementUnitController::class)->only('store');
    Route::get('financialcategories', [App\Http\Controllers\API\FinancialCategoryController::class, 'index']);

    Route::apiResource('stores', App\Http\Controllers\API\StoreController::class)->only(['index', 'show']);
    Route::apiResource('payment-methods', App\Http\Controllers\API\PaymentMethodController::class)->only(['index', 'show']);
    Route::apiResource('states', App\Http\Controllers\API\StateController::class)->only(['index', 'show']);
    Route::apiResource('cities', App\Http\Controllers\API\CityController::class)->only(['index', 'show']);
    Route::apiResource('parameters', App\Http\Controllers\API\ParameterController::class)->only(['index', 'show']);
    Route::apiResource('faqs', App\Http\Controllers\API\FaqController::class)->only(['index', 'show']);
    Route::apiResource('banners', App\Http\Controllers\API\BannerController::class)->only(['index', 'show']);


    Route::post('stores/{id}/pagseguro/notification',  App\Http\Controllers\API\NotificationPagseguroController::class);

});
