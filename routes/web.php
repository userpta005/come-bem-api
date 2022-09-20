<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\{
    BannerController,
    CityController,
    FaqController,
    LeadController,
    ParameterController,
    PasswordController,
    PermissionController,
    ProfileController,
    RoleController,
    SectionController,
    SettingsController,
    StateController,
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
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('leads', LeadController::class);
    Route::resource('tenants', TenantController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->only(['index', 'show']);
    Route::resource('parameters', ParameterController::class)->except(['create', 'store', 'destroy']);
    Route::resource('faqs', FaqController::class);
    Route::resource('banners', BannerController::class);
});

