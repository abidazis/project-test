<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoogleIntegrationController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk integrasi Google
    Route::get('/auth/google', [GoogleIntegrationController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleIntegrationController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::post('/google/drive/upload', [GoogleIntegrationController::class, 'uploadToDrive'])->name('google.drive.upload');
    Route::post('/google/calendar/create', [GoogleIntegrationController::class, 'createCalendarEvent'])->name('google.calendar.create');
});

// Baris ini wajib ada karena memanggil rute login/register milik Breeze
require __DIR__.'/auth.php';