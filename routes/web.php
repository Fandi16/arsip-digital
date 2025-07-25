<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Route;

// Redirect '/' ke /login
Route::redirect('/', '/login');

// Login Manual
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =============== ADMIN =====================
Route::middleware(['auth', 'can:manage-users'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    Route::get('archives/generate', [ArchivesController::class, 'generate'])->name('archives.generate');
    Route::post('archives/generate', [ArchivesController::class, 'storeGeneratedPDF'])->name('archives.storeGenerated');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('archives', ArchivesController::class)->except(['show']);
});

// =============== ADMIN MARKETING ===============
Route::middleware(['auth'])->prefix('admin-marketing')->name('admin_marketing.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin_marketing.dashboard');
    })->name('dashboard');

    Route::resource('archives', ArchivesController::class)->except(['show']);
});

// ================ MARKETING ===================
Route::middleware(['auth'])->prefix('marketing')->name('marketing.')->group(function () {
    Route::get('/dashboard', function () {
        return view('marketing.dashboard');
    })->name('dashboard');

    Route::resource('archives', ArchivesController::class)->except(['show']);
});
