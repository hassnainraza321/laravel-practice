<?php

use Illuminate\Support\Facades\Route;

use App\HTTP\Controllers\BaseController;
use App\HTTP\Controllers\AuthController;

Route::any('/login', [AuthController::class, 'login'])->name('login');
Route::any('/register', [AuthController::class, 'register'])->name('register');
Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [BaseController::class, 'index'])->name('user');
Route::any('/user/get/{id}', [BaseController::class, 'getUser'])->name('user.get');
