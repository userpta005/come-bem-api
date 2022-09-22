<?php

use App\Http\Controllers\API\{
    BannerController,
    CityController,
    FaqController,
    GetPersonByNifController,
    MeasurementUnitController,
    NcmController,
    ParameterController,
    PaymentMethodController,
    SectionController,
    SettingsController
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
    Route::get('ncms', [NcmController::class, 'search']);
    Route::get('get-person-by-nif', GetPersonByNifController::class);
    Route::resource('cities', CityController::class)->only(['index', 'show']);
    Route::get('parameters', [ParameterController::class, 'index']);

    Route::get('sections', [SectionController::class, 'index']);
    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::apiResource('measurement-units', MeasurementUnitController::class)->only('store');
    
    Route::get('settings', SettingsController::class);
    Route::get('faqs', [FaqController::class, 'index']);
    Route::get('banners', [BannerController::class, 'index']);
});
