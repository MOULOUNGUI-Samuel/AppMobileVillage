<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',  [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/listeReservation', [ReservationController::class, 'listeReservation'])->name('listeReservation');
    Route::get('/detailsReservation/{id}', [ReservationController::class, 'detailsReservation'])->name('detailsReservation');
    Route::get('/pdf/facture_directe/{id}', [PDFController::class, 'facture_directe'])->name('facture_directe');
    Route::get('/pdf/releve_client/{id}', [PDFController::class, 'releve_client'])->name('releve_client');
    

    Route::get('/finances', function () {
        return view('finances');
    })->name('finances');
    Route::get('/utilisateurs', function () {
        return view('utilisateurs');
    })->name('utilisateurs');
});

require __DIR__ . '/auth.php';

Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');
Route::get('/reservation/{id}/facture', [ReservationController::class, 'facture'])->name('reservation.facture');
Route::put('/reservation/{id}', [ReservationController::class, 'update'])->name('reservation.update');
Route::patch('/reservation/{id}/archive', [ReservationController::class, 'archive'])->name('reservation.archive');
Route::resource('reservations', ReservationController::class);
Route::get('/reservations/{id}/facture', [ReservationController::class, 'facture'])->name('reservations.facture');

Route::get('/profile-settings', function () {
    return view('profile-settings');
})->name('profile.settings');
