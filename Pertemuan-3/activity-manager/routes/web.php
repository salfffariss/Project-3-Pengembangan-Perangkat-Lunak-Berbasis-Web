<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('activities.index');
});

// Menangani otomatis 7 rute CRUD (index, create, store, show, edit, update, destroy)
Route::resource('activities', ActivityController::class);
