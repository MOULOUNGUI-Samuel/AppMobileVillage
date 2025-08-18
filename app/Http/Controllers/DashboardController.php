<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $entreprise_id = Auth::user()->entreprise_id;
        $reservations = Reservation::with(['client', 'user', 'salle'])
            ->whereHas('client', function ($query) use ($entreprise_id) {
                $query->where('entreprise_id', $entreprise_id);
            })
            ->orderBy('start_date', 'asc') // Tri décroissant
            ->where('end_date', '>=', now()) // ✅ encore à venir
            ->get();
        return view('dashboard', compact('reservations'));
    }
}
