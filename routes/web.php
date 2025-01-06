<?php

use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Rota para exibir a tela de login
Route::get('/', function () {
    return view('login.login'); // Carrega o arquivo login/login.blade.php
})->name('login');

Route::get('/forgot-password', function () {
    return view('password.forgot-password');
})->name('password.request');



Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])
    ->name('password.reset');
    


Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', function () {
    return view('login.login');
})->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
