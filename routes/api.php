<?php

use App\Http\Controllers\API\{
    AccountController,
    BannerController,
    CardController,
    ChangePasswordController,
    CityController,
    ClientController,
    DependentController,
    FaqController,
    FinancialCategoryController,
    GetPersonByNifController,
    LimitedProductController,
    MeasurementUnitController,
    NcmController,
    ParameterController,
    PaymentMethodController,
    PersonController,
    ProfileController,
    ResetPasswordController,
    SectionController,
    SessionController,
    SettingsController,
    StateController,
    StockController,
    UserController,
};
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
        Route::post('users', [UserController::class, 'store']);
        Route::post('login', [SessionController::class, 'store']);
        Route::post('reset-password', ResetPasswordController::class);

        Route::middleware('auth:sanctum')->group(function () {
            Route::delete('logout', [SessionController::class, 'destroy']);
            Route::get('profile', [ProfileController::class, 'show']);
            Route::put('profile', [ProfileController::class, 'update']);
            Route::post('change-password', ChangePasswordController::class);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('clients/{id}', [ClientController::class, 'destroy']);
        Route::delete('dependents/{id}', [DependentController::class, 'destroy']);
        Route::delete('accounts/{id}', [AccountController::class, 'destroy']);
        Route::delete('cards/{id}', [CardController::class, 'destroy']);
        Route::put('limited_products', [LimitedProductController::class, 'store']);
    });

    Route::get('get-person-by-nif', GetPersonByNifController::class);
    Route::get('people', [PersonController::class, 'search']);
    Route::get('ncms', [NcmController::class, 'search']);

    Route::get('stocks', [StockController::class, 'index']);
    Route::get('sections', [SectionController::class, 'index']);
    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::apiResource('measurement-units', MeasurementUnitController::class)->only('store');
    Route::get('financialcategories', [FinancialCategoryController::class, 'index']);

    Route::apiResource('states', StateController::class)->only(['index', 'show']);
    Route::apiResource('cities', CityController::class)->only(['index', 'show']);
    Route::apiResource('parameters', ParameterController::class)->only(['index', 'show']);
    Route::apiResource('faqs', FaqController::class)->only(['index', 'show']);
    Route::apiResource('banners', BannerController::class)->only(['index', 'show']);
    Route::apiResource('settings', SettingsController::class)->only(['index']);
});
