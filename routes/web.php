<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

#users
use App\Http\Controllers\UserController;
#company
use App\Http\Controllers\CompanyInfoController;
#slider
use App\Http\Controllers\SliderController;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(App\Http\Controllers\Auth\PasswordController::class)->group(function () {
        Route::get('/password/set', 'show')->name('password.set');
        Route::post('/password/set', 'store');
    });
});

Route::get('/', function () {
    return Inertia::render('Home/Index');
})->name('home');

Route::middleware('auth')->prefix('admin')->group(function () {
    /* Admin Users */
    Route::group(['prefix'=>'users'],function(){
        Route::get('/', function () {
            return Inertia::render('Admin/Users/Index');
        })->name('admin.users');

        Route::get('fetch', [UserController::class, 'fetchUsers'])->name('admin.users.fetch');
        Route::post('store', [UserController::class, 'store'])->name('admin.users.store');
        Route::post('update', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('delete', [UserController::class, 'delete'])->name('admin.users.delete');
    });

    Route::group(['prefix' => 'company'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Company/Index');
        })->name('admin.company');

        Route::get('fetch', [CompanyInfoController::class, 'getCompanyInfo'])->name('admin.company.fetch');
        Route::post('store', [CompanyInfoController::class, 'store'])->name('admin.company.store');
    });

    Route::group(['prefix' => 'sliders'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Slider/Index');
        })->name('admin.slider');

        Route::post('store', [SliderController::class, 'store'])->name('admin.slider.store');
        Route::post('update', [SliderController::class, 'update'])->name('admin.slider.update');
        Route::post('delete', [SliderController::class, 'delete'])->name('admin.slider.delete');
    });
});

Route::get('sliders/fetch', [SliderController::class, 'getSliders'])->name('admin.slider.fetch');



Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
require __DIR__.'/auth.php';
