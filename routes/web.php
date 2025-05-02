<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
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

Route::middleware(['auth', RoleMiddleware::class . ':admin'])->get('/admin', function () {
    return view('admin');
});

Route::middleware(['auth', RoleMiddleware::class . ':user'])->get('/user', function () {
    return view('user');
});

Route::middleware(['auth', RoleMiddleware::class . ':editor'])->get('/editor', function () {
    return view('editor');
});
