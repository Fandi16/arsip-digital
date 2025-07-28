<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ScannerController;
use Illuminate\Support\Facades\Route;

// Redirect '/' ke /login
Route::redirect('/', '/login');

// Login Manual
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'can:manage-users'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('archives', ArchivesController::class)->except(['show']);

    Route::get('scanner', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('scanner/upload', [ScannerController::class, 'upload'])->name('scanner.upload');
    Route::post('scanner/store', [ScannerController::class, 'store'])->name('scanner.store');
    Route::post('scanner/download-pdf', [ScannerController::class, 'downloadPdf'])->name('scanner.download-pdf');
    
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
