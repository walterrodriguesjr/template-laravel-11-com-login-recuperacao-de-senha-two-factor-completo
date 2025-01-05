<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Rota para exibir a tela de login
Route::get('/', function () {
    return view('login.login'); // Carrega o arquivo login/login.blade.php
})->name('login');


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
