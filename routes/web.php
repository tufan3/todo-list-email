<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('todos', TodoController::class);
Route::post('todos/{todo}/complete', [TodoController::class, 'complete'])->name('todos.complete');