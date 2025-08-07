<?php

namespace App\Http\Controllers;

use App\Models\ApprobaEvenement;
use App\Models\Caution;
use App\Models\MouvementCaisse;
use App\Models\Reservation;
use App\Models\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprobationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reservation_approbations = ApprobaEvenement::with(['reservation', 'reservation.client', 'user', 'reservation.salle'])
            ->where('entreprise_id', Auth::user()->entreprise_id)
            ->whereHas('reservation') // Vérifie que la relation "reservation" existe
            ->orderBy("created_at", "desc")
            ->get();

        $informations_reserves = [];
        foreach ($reservation_approbations as $reservation_approbation) {


            $caution = Caution::where('reservation_id', $reservation_approbation->reservation->id)->first();
            $total_services = ReservationService::where('reservation_id', $reservation_approbation->reservation->id)
                ->sum('prix_unitaire');
            $mouvements = MouvementCaisse::with(['reservation.user'])->where('reservation_id', $reservation_approbation->reservation->id)->get();
            // Récupérer les services liés à cette réservation
            $reservationServices = ReservationService::with('service')
                ->where('reservation_id', $reservation_approbation->reservation->id) // Filtre sur la réservation
                ->get()
                ->map(function ($resService) {
                    return [
                        'service_id' => $resService->service->id,
                        'service_nom' => $resService->service->nom,
                        'quantite' => $resService->quantite,
                        'prix_unitaire' => $resService->prix_unitaire,
                        'total' => $resService->quantite * $resService->prix_unitaire
                    ];
                });
            // $montant_restants = ($reservation_approbations->reservation->montant_quitance - $reservation_approbations->reservation->montant_payer) + ($caution->montant_caution - $caution->montant_rembourse);
            $informations_reserves[] = [
                'reservation' => [
                    'approbation_id' => $reservation_approbation->id,
                    'reservation_id' => $reservation_approbation->reservation_id,
                    'client_id' => $reservation_approbation->reservation->client->id,
                    'user_nom' => $reservation_approbation->user->nom . ' ' . $reservation_approbation->user->prenom,
                    'observation' => $reservation_approbation->observation,
                    'statut_approbation' => $reservation_approbation->statut,
                    'created_at_approbation' => $reservation_approbation->created_at,
                    'client_name' => $reservation_approbation->reservation->client->nom ?? 'Client inconnu',
                    'client_prenom' => $reservation_approbation->reservation->client->prenom ?? 'Client inconnu',
                    'salle' => $reservation_approbation->reservation->salle->nom ?? 'Salle inconnue',
                    'montant_salle' => $reservation_approbation->reservation->salle->montant_base,
                    'solde_salle' => $reservation_approbation->reservation->salle->montant_base ?? 'Salle inconnue',
                    'start_date' => $reservation_approbation->reservation->start_date,
                    'total_services' => $total_services,
                    'end_date' => $reservation_approbation->reservation->end_date,
                    'montant_total' => $reservation_approbation->reservation->montant_total,
                    // 'montant_restant' => $montant_restant,
                    'montant_quitance' => $reservation_approbation->reservation->montant_quitance,
                    'montant_payer' => $reservation_approbation->reservation->montant_payer,
                    'montant_reduction' => $reservation_approbation->reservation->montant_reduction,
                    'montant_quotion' => $caution->montant_caution,
                    'statut' => $reservation_approbation->reservation->statut,
                    'statut_valider' => $reservation_approbation->reservation->statut_valider,
                    'description' => $reservation_approbation->reservation->description,
                    'services' => $reservationServices, // Ajout des services liés
                    'mouvements' => $mouvements // Ajout des mouvements liés
                ]
            ];
        }
        // dd($informations_reserves);
        return view("components.approbations.approbations", compact('reservation_approbations', 'total_services', 'informations_reserves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function approbation_reservation($id, $reservation_id)
    {
        //
        // Trouver l'approbation d'événement et valider
        $approbevenement = ApprobaEvenement::find($id);
        if (!$approbevenement) {
            return redirect()->back()->with("error", "Approbation introuvable !");
        }

        $approbevenement->statut = "valide";
        $approbevenement->save();

        // Trouver la réservation et mettre à jour ses champs
        $reservation = Reservation::find($reservation_id);
        if (!$reservation) {
            return redirect()->back()->with("error", "Réservation introuvable !");
        }

        $reservation->statut_valider = 1;
        $reservation->description_rejet = null; // Null sans majuscule
        $reservation->save();

        return redirect()->back()->with("success", "Reservation validée avec succès !");
    }
    public function supprimer_approb_reservation(Request $request, $id)
    {
        //
        $approb = ApprobaEvenement::find($id);
        $approb->statut = "rejete";
        $approb->save();

        $reservation = Reservation::find($approb->reservation_id);
        $reservation->statut_valider = 0;
        $reservation->description_rejet = $request->description_rejet;
        $reservation->save();

        return redirect()->back()->with("success", "Rejet effectué avec succès !");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
