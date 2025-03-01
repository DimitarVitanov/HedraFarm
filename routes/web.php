<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

#users
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return Inertia::render('Home/Index');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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


});

require __DIR__.'/auth.php';
