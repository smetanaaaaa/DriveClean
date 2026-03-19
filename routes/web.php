<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\HomeController;
use App\Http\Controllers\Main\CleanController;
use App\Http\Controllers\Main\LoginController;
use App\Http\Controllers\Main\AdminController;
use App\Http\Controllers\Main\RegisterController;
use App\Http\Controllers\Main\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/how-it-works', [HomeController::class, 'how_it_works'])->name('how_it_works');
Route::get('/social', [HomeController::class, 'social'])->name('social');

Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'login_store'])->name('login.store');
Route::post('/clean/store', [CleanController::class, 'store'])->name('clean.store');

Route::middleware(['web','auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
      Route::get('/dashboard/download/{file}', [DashboardController::class, 'download'])->name('dashboard.download');
    Route::post('/dashboard/upload', [DashboardController::class, 'upload'])->name('dashboard.upload');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
