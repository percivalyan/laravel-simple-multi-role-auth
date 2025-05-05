<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\SessionLogController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin');
    })->name('admin.dashboard');

    Route::get('/logs', [SessionLogController::class, 'index'])->name('admin.logs');
});

Route::middleware(['auth', RoleMiddleware::class . ':user'])->get('/user', function () {
    return view('user');
});

Route::middleware(['auth', RoleMiddleware::class . ':editor'])->get('/editor', function () {
    return view('editor');
});
