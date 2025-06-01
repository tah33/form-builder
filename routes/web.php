<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('post.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::resource('forms', FormController::class)->except('create','edit');
    Route::post('config/form', [FormController::class,'configForm'])->name('config.form');
});
