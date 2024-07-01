<?php

use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\PartUsageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// WelcomeController
Route::get('/', [WelcomeController::class, 'show'])->name('show.welcome');
Route::post('/', [WelcomeController::class, 'store'])->name('store.welcome');

// SparePartController
Route::get('/spare-part-detail', [SparePartController::class, 'show_detail'])->name('show.spare.part.detail');

Route::middleware(['auth', 'verified'])->group(function () {

    // DashboardController
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('show.dashboard');

    Route::middleware(['role:super-admin'])->group(function () {

        // UserController
        Route::get('/users', [UserController::class, 'show'])->name('show.users');
        Route::delete('/user', [UserController::class, 'destroy'])->name('destroy.user');

        // RoleController
        Route::post('/assign-role', [RoleController::class, 'assignRoles'])->name('assign.role');
        Route::delete('/roles/remove', [RoleController::class, 'removeRoles'])->name('remove.role');

        // DeviceTypeController
        Route::get('/device-types', [DeviceTypeController::class, 'show'])->name('show.device.types');
        Route::post('/device-type', [DeviceTypeController::class, 'store'])->name('store.device.type');
        Route::put('/device-type', [DeviceTypeController::class, 'update'])->name('update.device.type');
        Route::delete('/device-type', [DeviceTypeController::class, 'destroy'])->name('destroy.device.type');

        // SparePartController
        Route::get('/spare-parts', [SparePartController::class, 'show'])->name('show.spare.parts');
        Route::post('/spare-part', [SparePartController::class, 'store'])->name('store.spare.part');
        Route::post('/spare-part-image', [SparePartController::class, 'update_image'])->name('update.spare.part.image');
        Route::put('/spare-part', [SparePartController::class, 'update'])->name('update.spare.part');
        Route::delete('/spare-part', [SparePartController::class, 'destroy'])->name('destroy.spare.part');

        // SettingController
        Route::get('/setting', [SettingController::class, 'show'])->name('setting.edit');

        // HeaderController
        Route::post('/setting-header', [HeaderController::class, 'store'])->name('store.header');
        Route::post('/setting-header-image', [HeaderController::class, 'update_image'])->name('update.header.image');
        Route::put('/setting-header', [HeaderController::class, 'update'])->name('update.header');
        Route::delete('/setting-header', [HeaderController::class, 'destroy'])->name('destroy.header');

        // CarouselController
        Route::post('/setting-carousel', [CarouselController::class, 'store'])->name('store.carousel');
        Route::post('/setting-carousel-image', [CarouselController::class, 'update_image'])->name('update.carousel.image');
        Route::put('/setting-carousel', [CarouselController::class, 'update'])->name('update.carousel');
        Route::delete('/setting-carousel', [CarouselController::class, 'destroy'])->name('destroy.carousel');

        // FooterController
        Route::post('/setting-footer', [FooterController::class, 'store'])->name('store.footer');
        Route::post('/setting-footer-image', [FooterController::class, 'update_image'])->name('update.footer.image');
        Route::put('/setting-footer', [FooterController::class, 'update'])->name('update.footer');
        Route::delete('/setting-footer', [FooterController::class, 'destroy'])->name('destroy.footer');

        Route::delete('/customer', [CustomerController::class, 'destroy'])->name('destroy.customer');
        Route::delete('/device', [DeviceController::class, 'destroy'])->name('destroy.device');
        Route::delete('/service', [ServiceController::class, 'destroy'])->name('destroy.service');
        Route::delete('/service-detail', [ServiceDetailController::class, 'destroy'])->name('destroy.service.detail');
        Route::delete('/part-usage', [PartUsageController::class, 'destroy'])->name('destroy.part.usage');
    });

    Route::middleware(['role:admin'])->group(function () {

        // CustomerController
        Route::get('/customers', [CustomerController::class, 'show'])->name('show.customers');
        Route::post('/customer', [CustomerController::class, 'store'])->name('store.customer');
        Route::put('/customer', [CustomerController::class, 'update'])->name('update.customer');

        // DeviceController
        Route::get('/devices', [DeviceController::class, 'show'])->name('show.devices');
        Route::post('/device', [DeviceController::class, 'store'])->name('store.device');
        Route::put('/device', [DeviceController::class, 'update'])->name('update.device');

        // ServiceController
        Route::get('/services', [ServiceController::class, 'show'])->name('show.services');
        Route::post('/service', [ServiceController::class, 'store'])->name('store.service');
        Route::put('/service', [ServiceController::class, 'update'])->name('update.service');

        Route::get('service/print/{service_code}', [ServiceController::class, 'print'])->name('service.print');
    });

    Route::middleware(['role:user'])->group(function () {

        // ServiceDetailController
        Route::get('/service-details', [ServiceDetailController::class, 'show'])->name('show.service.details');
        Route::post('/service-detail', [ServiceDetailController::class, 'store'])->name('store.service.detail');
        Route::put('/service-detail', [ServiceDetailController::class, 'update'])->name('update.service.detail');

        Route::get('service/detail/print/{service_detail_code}', [ServiceDetailController::class, 'print'])->name('service.detail.print');

        // PartUsageController
        Route::get('/part-usages', [PartUsageController::class, 'show'])->name('show.part.usages');
        Route::post('/part-usage', [PartUsageController::class, 'store'])->name('store.part.usage');
        Route::put('/part-usage', [PartUsageController::class, 'update'])->name('update.part.usage');
    });
});

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
