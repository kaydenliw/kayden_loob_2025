<?php

use App\Http\Controllers\RecruiterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Auth::routes();

// Home Route (redirects to appropriate dashboard)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Recruiter Dashboard Routes
Route::prefix('recruiter')->name('recruiter.')->group(function () {
    Route::get('/', [RecruiterController::class, 'dashboard'])->name('dashboard');
    Route::get('/application/{application}', [RecruiterController::class, 'show'])->name('show');
    Route::patch('/application/{application}/status', [RecruiterController::class, 'updateStatus'])->name('updateStatus');
});
