<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservationController extends Controller
{
    /**
     * Affiche la liste des réservations.
     */
    public function index()
    {
        $reservations = Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Affiche le formulaire de création d'une réservation.
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Enregistre une nouvelle réservation.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'reference' => 'required|unique:reservations',
        'client_name' => 'required',
        'salle' => 'required',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'montant_total' => 'required|integer',
        'statut' => 'required|in:en attente,confirmée,annulée,archivée',
    ]);

        
        Reservation::create($request->all());

        return redirect()->route('reservations.index')->with('success', 'Réservation ajoutée avec succès');
    }

    /**
     * Affiche les détails d'une réservation.
     */
    public function show(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservation-details', compact('reservation'));
    }

    /**
     * Télécharge la facture PDF.
     */
    public function facture($id)
    {
        $reservation = Reservation::findOrFail($id);
        $file = "factures/facture_{$reservation->id}.pdf";

        if (!Storage::disk('local')->exists($file)) {
            return back()->with('error', 'Facture non trouvée.');
        }

        return response()->download(storage_path("app/{$file}"));
    }

    /**
     * Archive une réservation.
     */
    public function archive($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->statut = 'archivée';
        $reservation->save();
        return redirect()->back()->with('success', 'Réservation archivée');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Met à jour une réservation existante.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_name' => 'required',
            'salle' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'montant_total' => 'required|integer',
            'statut' => 'required',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour avec succès');
    }

    /**
     * Supprime une réservation.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée');
    }

    public function dashboard()
    {
        $reservations = Reservation::all();
        return view('dashboard', compact('reservations'));
    }

}
