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
        Route::post('users', [App\Http\Controllers\API\UserController::class, 'store']); //ok
        Route::post('login', [App\Http\Controllers\API\SessionController::class, 'store']); //ok
        Route::post('forgot-password', App\Http\Controllers\API\ForgotPasswordController::class);
        Route::post('reset-password', App\Http\Controllers\API\ResetPasswordController::class);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('users', [App\Http\Controllers\API\UserController::class, 'show']); //ok
            Route::delete('logout', [App\Http\Controllers\API\SessionController::class, 'destroy']); //ok
            Route::get('profile', [App\Http\Controllers\API\ProfileController::class, 'show']);
            Route::put('profile', [App\Http\Controllers\API\ProfileController::class, 'update']);
            Route::post('change-password', App\Http\Controllers\API\ChangePasswordController::class);
        });
    });

    Route::middleware(['app'])->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            // desativar consumidor - ok
            Route::put('accounts/{id}/block', [App\Http\Controllers\API\AccountController::class, 'block']);
            // desativar dispositovs - ok
            Route::put('cards/block', [App\Http\Controllers\API\CardController::class, 'block']);
            // realizar recarga de cŕedito - ok
            Route::post('accounts/{id}/credit-purchases', [App\Http\Controllers\API\CreditPurchasesController::class, 'store']);
            // alterar limite diário - update ok
            Route::apiResource('accounts', App\Http\Controllers\API\AccountController::class)->only(['show', 'update']);
            // restringir produtos - ok
            Route::put('accounts/{id}/limited-products', [App\Http\Controllers\API\LimitedProductController::class, 'update']);
        });

        // listar categorias - ok
        Route::apiResource('sections', App\Http\Controllers\API\SectionController::class)->only(['index', 'show']);
        // listar produtos - ok
        Route::get('accounts/{id}/products', [App\Http\Controllers\API\ProductController::class, 'index']);

        Route::apiResource('leads', App\Http\Controllers\API\LeadController::class)->only(['store']);
        Route::apiResource('settings', App\Http\Controllers\API\SettingsController::class)->only(['index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('dependents/{id}/create-user', [App\Http\Controllers\API\DependentController::class, 'createUser']); //ok
        Route::apiResource('clients.dependents', App\Http\Controllers\API\DependentController::class)->except('destroy');
    });

    //Apis para deletar models pelo back office
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('clients/{id}', [App\Http\Controllers\API\ClientController::class, 'destroy']);
        Route::delete('dependents/{id}', [App\Http\Controllers\API\DependentController::class, 'destroy']);
        Route::delete('accounts/{id}', [App\Http\Controllers\API\AccountController::class, 'destroy']);
        Route::delete('cards/{id}', [App\Http\Controllers\API\CardController::class, 'destroy']);
    });

    Route::get('get-person-by-nif', App\Http\Controllers\API\GetPersonByNifController::class);
    Route::get('people', [App\Http\Controllers\API\PersonController::class, 'search']);
    Route::get('ncms', [App\Http\Controllers\API\NcmController::class, 'search']);

    Route::apiResource('stores', App\Http\Controllers\API\StoreController::class)->only(['index', 'show']);
    Route::apiResource('payment-methods', App\Http\Controllers\API\PaymentMethodController::class)->only(['index', 'show']);
    Route::apiResource('states', App\Http\Controllers\API\StateController::class)->only(['index', 'show']);
    Route::apiResource('cities', App\Http\Controllers\API\CityController::class)->only(['index', 'show']);
    Route::apiResource('parameters', App\Http\Controllers\API\ParameterController::class)->only(['index', 'show']);
    Route::apiResource('faqs', App\Http\Controllers\API\FaqController::class)->only(['index', 'show']);
    Route::apiResource('banners', App\Http\Controllers\API\BannerController::class)->only(['index', 'show']);
});
