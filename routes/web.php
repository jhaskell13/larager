<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->to('/tasks');
})->name('home');

Route::resource('tasks', TaskController::class);
Route::post('tasks/reorder', [TaskController::class, 'reorder']);
