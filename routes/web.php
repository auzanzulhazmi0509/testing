<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidatorController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'a', 'midddleware' => ['auth', 'isAdmin']], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('a.dashboard');
});

Route::group(['prefix' => 'u', 'midddleware' => ['auth', 'isUser']], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('u.dashboard');
    Route::post('/execute-code', [ValidatorController::class, 'execute_code'])->name('u.executecode');
});
