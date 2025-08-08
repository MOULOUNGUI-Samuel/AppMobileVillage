<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\GestionFinanceController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',  [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/utilisateurs', [EmployerController::class, 'index'])->name('usersListe');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/listeReservation', [ReservationController::class, 'listeReservation'])->name('listeReservation');
    Route::get('/detailsReservation/{id}', [ReservationController::class, 'detailsReservation'])->name('detailsReservation');
    Route::get('/pdf/facture_directe/{id}', [PDFController::class, 'facture_directe'])->name('facture_directe');
    Route::get('/pdf/releve_client/{id}', [PDFController::class, 'releve_client'])->name('releve_client');
    
    
    
    Route::get('/Caisses', [GestionFinanceController::class, 'index'])->name('liscteCaisse');
    Route::get('/pdf/mouvement_caisse/{id}', [PDFController::class, 'mouvementCaisse'])->name('pdf.mouvement_caisse');
    
});

require __DIR__ . '/auth.php';
