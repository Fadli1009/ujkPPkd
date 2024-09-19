<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('registrasi', [AuthController::class, 'register'])->name('register');
Route::post('registrasi/func', [AuthController::class, 'registerFunc'])->name('register.func');
Route::post('login/func', [AuthController::class, 'funcLogin'])->name('funcLogin');
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profiles', [UserController::class, 'updateProfile'])->name('update.profile');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dataPost', [PostController::class, 'filterPost'])->name('filerPost');
    Route::resource('users', UserController::class);
    Route::resource('post', PostController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('likes', LikeController::class);
    Route::delete('users/delete/{id}', [UserController::class, 'delete'])->name('delete.users');
});
