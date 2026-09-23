<?php

use App\Http\Controllers\ActivityController; // <-- BARIS INI WAJIB ADA
use Illuminate\Support\Facades\Route;

// Redirect halaman utama ke daftar kegiatan
Route::get('/', function () {
    return redirect('/activities');
});

// Route daftar dan detail
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');