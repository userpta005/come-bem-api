<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\{
    AccountController,
    BannerController,
    CardController,
    ChangeFirtsPasswordController,
    ChangeStoreSessionController,
    CityController,
    ClientController,
    DependentController,
    DevolutionController,
    FaqController,
    FinancialCategoryController,
    LeadController,
    LimitedProductController,
    MeasurementUnitController,
    NcmController,
    OpeningBalanceController,
    ParameterController,
    PasswordController,
    PaymentMethodController,
    PermissionController,
    ProductController,
    ProfileController,
    RequisitionController,
    RoleController,
    SectionController,
    SettingsController,
    StateController,
    StockController,
    StoreController,
    TenantController,
    UserController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::get('states', [StateController::class, 'index'])->name('states.index');
    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('ncms', [NcmController::class, 'index'])->name('ncms.index');
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('change-first-password', [ChangeFirtsPasswordController::class, 'edit'])->name('change-first-password.edit');
    Route::put('change-first-password', [ChangeFirtsPasswordController::class, 'update'])->name('change-first-password.update');
    Route::get('change-store/{id}', ChangeStoreSessionController::class)->name('change.store');
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('stock', [StockController::class, 'show'])->name('stocks.show');
    Route::get('accounts/{account}/limited_products', [LimitedProductController::class, 'index'])->name('accounts.limited_products.index');

    Route::resource('leads', LeadController::class);
    Route::resource('tenants', TenantController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('clients.dependents', DependentController::class);
    Route::resource('dependents.accounts', AccountController::class);
    Route::resource('accounts.cards', CardController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('measurement-units', MeasurementUnitController::class);
    Route::resource('products', ProductController::class);
    Route::resource('requisitions', RequisitionController::class)->except(['edit', 'update']);
    Route::resource('devolutions', DevolutionController::class)->except(['edit', 'update']);
    Route::resource('openingbalances', OpeningBalanceController::class);
    Route::resource('financialcategories', FinancialCategoryController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->only(['index', 'show']);
    Route::resource('parameters', ParameterController::class)->except(['create', 'store', 'destroy']);
    Route::resource('faqs', FaqController::class);
    Route::resource('banners', BannerController::class);
});

