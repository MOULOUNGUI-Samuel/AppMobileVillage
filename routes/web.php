<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');
Route::get('/finances', function () {
    return view('finances');
})->name('finances');
Route::get('/utilisateurs', function () {
    return view('utilisateurs');
})->name('utilisateurs');
});

require __DIR__.'/auth.php';

Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');
Route::get('/reservation/{id}/facture', [ReservationController::class, 'facture'])->name('reservation.facture');
Route::put('/reservation/{id}', [ReservationController::class, 'update'])->name('reservation.update');
Route::patch('/reservation/{id}/archive', [ReservationController::class, 'archive'])->name('reservation.archive');
Route::resource('reservations', ReservationController::class);
Route::get('/reservations/{id}/facture', [ReservationController::class, 'facture'])->name('reservations.facture');
Route::get('/doctor-profile', function () {
    return view('doctor-profile');
})->name('doctor.profile');
Route::get('/profile-settings', function () {
    return view('profile-settings');
})->name('profile.settings');




