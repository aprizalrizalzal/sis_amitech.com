<?php

use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\PartUsageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// WelcomeController
Route::get('/', [WelcomeController::class, 'show'])->name('show.welcome');
Route::post('/', [WelcomeController::class, 'store'])->name('store.welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    // DashboardController
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('show.dashboard');

    Route::middleware(['role:super admin'])->group(function () {

        // UserController
        Route::get('/users', [UserController::class, 'show'])->name('show.users');
        Route::delete('/user', [UserController::class, 'destroy'])->name('destroy.user');

        // DeviceTypeController
        Route::get('/deviceTypes', [DeviceTypeController::class, 'show'])->name('show.device.types');
        Route::post('/deviceType', [DeviceTypeController::class, 'store'])->name('store.device.type');
        Route::put('/deviceType', [DeviceTypeController::class, 'update'])->name('update.device.type');
        Route::delete('/deviceType', [DeviceTypeController::class, 'destroy'])->name('destroy.device.type');

        // SparePartController
        Route::get('/spareParts', [SparePartController::class, 'show'])->name('show.spare.parts');
        Route::post('/sparePart', [SparePartController::class, 'store'])->name('store.spare.part');
        Route::put('/sparePart', [SparePartController::class, 'update'])->name('update.spare.part');
        Route::delete('/sparePart', [SparePartController::class, 'destroy'])->name('destroy.spare.part');

        // CarouselController
        Route::post('/carousel', [CarouselController::class, 'store'])->name('store.carousel');
        Route::put('/carousel', [CarouselController::class, 'update'])->name('update.carousel');
        Route::delete('/carousel', [CarouselController::class, 'destroy'])->name('destroy.carousel');

        // SettingController
        Route::get('/setting', [SettingController::class, 'show'])->name('setting.edit');

    });

    Route::middleware(['role:admin'])->group(function () {

        // CustomerController
        Route::get('/customers', [CustomerController::class, 'show'])->name('show.customers');
        Route::post('/customer', [CustomerController::class, 'store'])->name('store.customer');
        Route::put('/customer', [CustomerController::class, 'update'])->name('update.customer');
        Route::delete('/customer', [CustomerController::class, 'destroy'])->name('destroy.customer');

        // DeviceController
        Route::get('/devices', [DeviceController::class, 'show'])->name('show.devices');
        Route::post('/device', [DeviceController::class, 'store'])->name('store.device');
        Route::put('/device', [DeviceController::class, 'update'])->name('update.device');
        Route::delete('/device', [DeviceController::class, 'destroy'])->name('destroy.device');

        // ServiceController
        Route::get('/services', [ServiceController::class, 'show'])->name('show.services');
        Route::post('/service', [ServiceController::class, 'store'])->name('store.service');
        Route::put('/service', [ServiceController::class, 'update'])->name('update.service');
        Route::delete('/service', [ServiceController::class, 'destroy'])->name('destroy.service');

        Route::get('service/print/{service_code}', [ServiceController::class, 'print'])->name('service.print');
    });

    Route::middleware(['role:technician'])->group(function () {

        // ServiceDetailController
        Route::get('/serviceDetails', [ServiceDetailController::class, 'show'])->name('show.service.details');
        Route::post('/serviceDetail', [ServiceDetailController::class, 'store'])->name('store.service.detail');
        Route::put('/serviceDetail', [ServiceDetailController::class, 'update'])->name('update.service.detail');
        Route::delete('/serviceDetail', [ServiceDetailController::class, 'destroy'])->name('destroy.service.detail');

        Route::get('service/detail/print/{service_detail_code}', [ServiceDetailController::class, 'print'])->name('service.detail.print');

        // PartUsageController
        Route::get('/partUsages', [PartUsageController::class, 'show'])->name('show.part.usages');
        Route::post('/partUsage', [PartUsageController::class, 'store'])->name('store.part.usage');
        Route::put('/partUsage', [PartUsageController::class, 'update'])->name('update.part.usage');
        Route::delete('/partUsage', [PartUsageController::class, 'destroy'])->name('destroy.part.usage');
    });
});

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/unauthorized', function () {
    return Inertia::render('Unauthorized/Unauthorized');
})->name('unauthorized');

require __DIR__ . '/auth.php';
