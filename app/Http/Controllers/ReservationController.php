<?php

namespace App\Http\Controllers;

use App\Models\ApprobaEvenement;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\ReservationService;
use App\Models\Salle;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use App\Models\Caution;
use App\Models\MouvementCaisse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    public function index()
    {

        try {
            $entreprise_id = Auth::user()->entreprise_id;
            // Récupération de toutes les service dans la base de données
            $clients = Client::with('entreprise')
                ->where('entreprise_id', $entreprise_id)
                ->get();

            $services = Service::with('entreprise')
                ->where('entreprise_id', $entreprise_id)
                ->get();
            $salles = Salle::with('entreprise')
                ->where('entreprise_id', $entreprise_id)
                ->get();
            return view('dashboard', compact('clients', 'services', 'salles'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function liste_reservation()
    {
        $entreprise_id = Auth::user()->entreprise_id;
        $reservations = Reservation::with(['client', 'user', 'salle'])
            ->whereHas('client', function ($query) use ($entreprise_id) {
                $query->where('entreprise_id', $entreprise_id);
            })
            ->orderBy('start_date', 'desc') // Tri décroissant
            ->get();
        // dd($reservations);
        $informations_reserves = [];
        foreach ($reservations as $reservation) {

            $caution = Caution::where('reservation_id', $reservation->id)->first();
            $approbation = ApprobaEvenement::where('reservation_id', $reservation->id)->first();
            $mouvements = MouvementCaisse::with(['reservation.user'])->where('reservation_id', $reservation->id)->get();
            $total_services = ReservationService::where('reservation_id', $reservation->id)
                ->sum('prix_unitaire');
            // Récupérer les services liés à cette réservation
            $reservationServices = ReservationService::with('service')
                ->where('reservation_id', $reservation->id) // Filtre sur la réservation
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
                    'id' => $reservation->id,
                    'ref_quitance' => $reservation->ref_quitance,
                    'client_id' => $reservation->client->id,
                    'client_nom' => $reservation->client->nom . ' ' . $reservation->client->prenom,
                    'salle' => $reservation->salle->nom ?? 'Salle inconnue',
                    'montant_salle' => $reservation->salle->montant_base,
                    'solde_salle' => $reservation->salle->montant_base ?? 'Salle inconnue',
                    'start_date' => $reservation->start_date,
                    'total_services' => $total_services,
                    'end_date' => $reservation->end_date,
                    'montant_total' => $reservation->montant_total,
                    // 'montant_restant' => $montant_restant,
                    'montant_quitance' => $reservation->montant_quitance,
                    'montant_payer' => $reservation->montant_payer,
                    'montant_reduction' => $reservation->montant_reduction,
                    'montant_quotion' => $caution->montant_caution,
                    'statut' => $reservation->statut,
                    'statut_valider' => $reservation->statut_valider,
                    'description_rejet' => $reservation->description_rejet,
                    'statut_approbation' => $approbation->statut ?? 'valide',
                    'description' => $reservation->description,
                    'services' => $reservationServices, // Ajout des services liés
                    'total_services' => $total_services, // Ajout des services liés
                    'mouvements' => $mouvements // Ajout des mouvements liés
                ]
            ];
        }
        // dd($informations_reserves);
        return view('components.liste_reservations', compact('informations_reserves'));
    }
    public function formulaire_modif_reservation($id)
    {
        $entreprise_id = Auth::user()->entreprise_id;
        $clients = Client::with('entreprise')
            ->where('entreprise_id', $entreprise_id)
            ->get();

        $services = Service::with('entreprise')
            ->where('entreprise_id', $entreprise_id)
            ->get();
        $salles = Salle::with('entreprise')
            ->where('entreprise_id', $entreprise_id)
            ->get();

        $caution = Caution::where('reservation_id', $id)->first();

        $reservations = Reservation::with('client', 'salle')
            ->where('id', $id)
            ->first();

        $reservationServices = ReservationService::with('service')
            ->where('reservation_id', $id) // Filtre sur la réservation
            ->get();

        return view('components.reservations.modif_reservation', compact('reservations', 'caution', 'clients', 'services', 'salles', 'reservationServices'));
    }
    public function store(Request $request)
    {

        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'nom_client' => 'required|string|max:255',
            'nom_salle' => 'required|string|max:255',
            'montant' => 'required',
            'caution' => 'required',
            'montant_reduction' => 'nullable',
            'montant_total' => 'required',
            'description' => 'nullable|string',
        ], [
            'start_date.required' => 'Veuillez sélectionner une date de début.',
            'end_date.required' => 'Veuillez sélectionner une date de fin.',
            'nom_client.required' => 'Veuillez sélectionner un client.',
            'nom_salle.required' => 'Veuillez sélectionner une salle.',
            'montant.required' => 'Le montant de la salle est obligatoire.',
            'caution.required' => 'Le montant de la caution est obligatoire.',
            'montant_total.required' => 'Le montant total à payer est obligatoire.',
        ]);

        // Redirection avec erreurs si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // $start_date=$request->start_date;
        // $end_date=$request->end_date;

        // $salles = Reservation::whereBetween('start_date', [$start_date, $end_date])->get();
        // dd($salles);

        // try {
        // Recherche du client et de la salle
        $client = Client::where('nom', $request->nom_client)->first();
        $salle = Salle::where('nom', $request->nom_salle)->first();


        // $user = Auth::user();

        if (!$client) {
            return redirect()->back()->withErrors('Client introuvable, merci de vérifier l\'orthographe.')->withInput();
        }
        if (!$salle) {
            return redirect()->back()->withErrors('Salle introuvable, merci de vérifier l\'orthographe.')->withInput();
        }

        // Création de la réservation
        $user = Auth::user();
        $id = $user->id;
        $role_user = $user->role_user;
        $reservation = new Reservation();
        $reservation->id = Str::uuid(); // Générer un UUID
        $reservation->ref_quitance = 'FACT-' . date('Y-m-d H:i:s');
        $reservation->client_id = $client->id;
        $reservation->user_id = $id;
        $reservation->salle_id = $salle->id;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        // Suppression des espaces dans les montants
        $montant_total = preg_replace('/\s+/u', '', htmlspecialchars($request->montant_total)) ?? 0;
        $caution = preg_replace('/\s+/u', '', htmlspecialchars($request->caution)) ?? 0;
        $reservation->montant_quitance = $montant_total - $caution;
        $reservation->montant_payer = 0;
        $reservation->montant_reduction = preg_replace('/\s+/u', '', htmlspecialchars($request->montant_reduction)) ?? 0;
        $reservation->montant_total = preg_replace('/\s+/u', '', htmlspecialchars($request->montant_total)) ?? 0;
        if ($role_user == 'Admin') {
            $reservation->statut_valider = 1;
        };
        $reservation->statut = 'en attente';
        $reservation->description = $request->description ?? "";
        $reservation->save();

        // Enregistrement des services sélectionnés
        if ($request->services) {
            foreach ($request->services as $serviceId) {
                $service = Service::find($serviceId);

                $reservationService = new ReservationService(); // Correction de l'instance
                $reservationService->id = Str::uuid(); // Générer un UUID unique
                $reservationService->reservation_id = $reservation->id;
                $reservationService->service_id = $serviceId;
                $reservationService->quantite = 1; // Vous pouvez ajuster selon votre logique
                $reservationService->prix_unitaire = $service->tarif;
                $reservationService->save();
            }
        }
        // Enregistrement de la caution
        $caution = new Caution();
        $caution->id = Str::uuid();
        $caution->ref_caution = 'CAUT-' . date('Y-m-d H:i:s');
        $caution->reservation_id = $reservation->id;
        $caution->montant_caution = preg_replace('/\s+/u', '', htmlspecialchars($request->caution));
        $caution->montant_rembourse = 0;
        $caution->statut = 'en attente';
        $caution->save();

        $approbaEvenement = new ApprobaEvenement();
        $approbaEvenement->id = Str::uuid();
        $approbaEvenement->reservation_id = $reservation->id;
        $approbaEvenement->user_id = $id;
        $approbaEvenement->entreprise_id = Auth::user()->entreprise_id;
        $approbaEvenement->observation = "Demande de validation d'une reservation";
        if ($role_user != 'Admin') {
            $approbaEvenement->statut = 'en_attente';
        } else {
            $approbaEvenement->statut = 'valide';
        };
        $approbaEvenement->save();

        return redirect()->back()->with('success', 'Réservation enregistrée avec succès !');
        // } catch (Exception $e) {
        //     return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        // }

    }
    public function update(Request $request, $id)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'nom_client' => 'required|string|max:255',
            'nom_salle' => 'required|string|max:255',
            'montant' => 'required',
            'caution' => 'required',
            'montant_reduction' => 'nullable',
            'montant_total' => 'required',
            'description' => 'nullable|string',
        ], [
            'start_date.required' => 'Veuillez sélectionner une date de début.',
            'end_date.required' => 'Veuillez sélectionner une date de fin.',
            'nom_client.required' => 'Veuillez sélectionner un client.',
            'nom_salle.required' => 'Veuillez sélectionner une salle.',
            'montant.required' => 'Le montant de la salle est obligatoire.',
            'caution.required' => 'Le montant de la caution est obligatoire.',
            'montant_total.required' => 'Le montant total à payer est obligatoire.',
        ]);

        // Redirection avec erreurs si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $id_client = Auth::user()->id;
        // try {


        // Recherche du client et de la salle
        $client = Client::where('nom', $request->nom_client)->first();
        $salle = Salle::where('nom', $request->nom_salle)->first();

        if (!$client) {
            return redirect()->back()->withErrors('Client introuvable, merci de vérifier l\'orthographe.')->withInput();
        }
        if (!$salle) {
            return redirect()->back()->withErrors('Salle introuvable, merci de vérifier l\'orthographe.')->withInput();
        }
        // Suppression des espaces dans les montants
        $montant_total = preg_replace('/\s+/u', '', htmlspecialchars($request->montant_total)) ?? 0;
        $caution = preg_replace('/\s+/u', '', htmlspecialchars($request->caution)) ?? 0;
        // Récupération de la réservation existante
        $reservation = Reservation::findOrFail($id);
        // Mise à jour des informations de la réservation
        $reservation->client_id = $client->id;
        $reservation->salle_id = $salle->id;
        $reservation->user_id = $id_client;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;

        $reservation->montant_quitance = $montant_total - $caution;
        $reservation->montant_reduction = preg_replace('/\s+/u', '', htmlspecialchars($request->montant_reduction)) ?? 0;
        $reservation->montant_total = $montant_total;
        $reservation->description = $request->description ?? "";

        $reservation->save();

        // Suppression des anciens services liés à la réservation
        ReservationService::where('reservation_id', $reservation->id)->delete();
        if ($request->services) {
            foreach ($request->services as $serviceId) {
                $service = Service::find($serviceId);

                $reservationService = new ReservationService();
                $reservationService->id = Str::uuid();
                $reservationService->reservation_id = $reservation->id;
                $reservationService->service_id = $serviceId;
                $reservationService->quantite = 1;
                $reservationService->prix_unitaire = $service->tarif;
                $reservationService->save();
            }
        }

        // Mise à jour de la caution associée
        $caution = Caution::where('reservation_id', $reservation->id)->first();
        if ($caution) {
            $caution->montant_caution = preg_replace('/\s+/u', '', htmlspecialchars($request->caution));
            $caution->save();
        }

        return redirect()->route('dashboard')->with('success', 'Réservation mise à jour avec succès !');
        // } catch (Exception $e) {
        //     return redirect()->back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        // }
    }

    public function SalleReservation(Request $request)
    {
        $end_date = $request->query('end_date');
        $start_date = $request->query('start_date');

        $salles = Salle::whereDoesntHave('reservations', function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        })->get();

        return response()->json([
            'success' => true,
            'caisses' => $salles->map(function ($item) {
                return [
                    'nom' => $item->nom,
                ];
            }),
        ]);
    }
    public function SalleInfo(Request $request)
    {
        $nom_salle = $request->query('nom_salle');

        $salle = Salle::where('nom', $nom_salle)->first();

        // Log::info('ID de la caisse sélectionnée : ' . $nom_salle);
        if (!$salle) {
            return response()->json([
                'success' => false,
                'message' => 'Salle non trouvée'
            ]);
        }

        return response()->json([
            'success' => true,
            'salle' => [
                'nom' => $salle->nom,
                'montant' => $salle->montant_base,
                'caution' => $salle->caution,
            ]
        ]);
    }
    public function infoService(Request $request)
    {
        $nom_service = $request->query('nom_service');
        $service = Service::where('nom', $nom_service)->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service non trouvé.'
            ]);
        }

        return response()->json([
            'success' => true,
            'service' => [
                'nom' => $service->nom,
                'montant' => $service->tarif,
            ]
        ]);
    }

    public function fetchEvents()
    {
        try {
            $entreprise_id = Auth::user()->entreprise_id;

            $reservations = Reservation::with(['client', 'salle'])
                ->whereHas('salle', function ($query) use ($entreprise_id) {
                    $query->where('entreprise_id', $entreprise_id);
                })
                ->select(
                    'id',
                    'client_id',
                    'user_id',
                    'salle_id',
                    'ref_quitance',
                    'start_date',
                    'end_date',
                    'montant_total',
                    'montant_payer',
                    'montant_quitance',
                    'montant_reduction',
                    'statut',
                    'statut_valider',
                    'description'
                )
                ->get();


            $data = [];

            foreach ($reservations as $reservation) {
                $caution = Caution::where('reservation_id', $reservation->id)->first();
                $mouvements = MouvementCaisse::with(['reservation.user'])->where('reservation_id', $reservation->id)->get();
                $nbr_mouvement = count($mouvements);
                $total_services = ReservationService::where('reservation_id', $reservation->id)
                    ->sum('prix_unitaire');


                $approbaEvenement = ApprobaEvenement::where('reservation_id', $reservation->id)->first();
                // Récupérer les services liés à cette réservation
                $reservationServices = ReservationService::with('service')
                    ->where('reservation_id', $reservation->id) // Filtre sur la réservation
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
                $montant_restants = ($reservation->montant_quitance - $reservation->montant_payer) + ($caution->montant_caution - $caution->montant_rembourse);
                $montant_facture_restant = $reservation->montant_quitance - $reservation->montant_payer;
                $montant_facture_restant_cacher = $reservation->montant_quitance - $reservation->montant_payer;
                $montant_caution_restant = $caution->montant_caution - $caution->montant_rembourse;
                $montant_caution_restant_cacher = $caution->montant_caution - $caution->montant_rembourse;
                if ($reservation->statut_valider == 1) {
                    $montant_restant = number_format($montant_restants, 0, ',', ' ') . " FCFA";
                } elseif ($reservation->statut_valider == 0 && $approbaEvenement->statut == "rejete") {
                    $montant_restant = "Reservation rejetée";
                } else {
                    if ($montant_restants <= 0) {
                        $montant_restant = "";
                    } else {
                        $montant_restant = "En attente de validation";
                    }
                }
                // Ajouter l'entrée dans le tableau $data
                $data[] = [
                    'reservation' => [
                        'id' => $reservation->id,
                        'client_name' => $reservation->client->nom ?? 'Client inconnu',
                        'id_client' => $reservation->client_id ?? 'Client inconnu',
                        'client_prenom' => $reservation->client->prenom ?? 'Client inconnu',
                        'salle' => $reservation->salle->nom ?? 'Salle inconnue',
                        'solde_salle' => $reservation->salle->montant_base ?? 'Salle inconnue',
                        'start_date' => $reservation->start_date,
                        'total_services' => $total_services,
                        'end_date' => $reservation->end_date,
                        'montant_total' => $reservation->montant_total,
                        'montant_restant' => $montant_restant,
                        'montant_quitance' => $reservation->montant_quitance,
                        'montant_payer' => $reservation->montant_payer,
                        'montant_reduction' => $reservation->montant_reduction,
                        'montant_quotion' => $caution->montant_caution,
                        'statut' => $reservation->statut,
                        'montant_facture_restant' => $montant_facture_restant,
                        'montant_facture_restant_cacher' => $montant_facture_restant_cacher,
                        'montant_caution_restant' => $montant_caution_restant,
                        'montant_caution_restant_cacher' => $montant_caution_restant_cacher,
                        'statut_valider' => $reservation->statut_valider,
                        'description' => $reservation->description,
                        'services' => $reservationServices, // Ajout des services liés
                        'nbr_mouvement' => $nbr_mouvement // Ajout des nbr_mouvement liés
                    ]
                ];
            }
            // Formatter les réservations pour un affichage correct dans FullCalendar
            $formattedEvents = collect($data)->map(function ($data_liste) {
                $reservation = $data_liste['reservation'];

                if ($reservation['montant_reduction'] == 0) {
                    $montant_reduction = number_format($reservation['montant_reduction'], 0, ',', ' ') . ' FCFA';
                } else {
                    $montant_reduction = '-' . number_format($reservation['montant_reduction'], 0, ',', ' ') . ' FCFA';
                }

                return [
                    'id' => $reservation['id'],
                    'montant_restant' => $reservation['montant_restant'],
                    'title' => ($reservation['client_name'] ?? "Client inconnu") . " " .
                        ($reservation['client_prenom'] ?? "Client inconnu") .
                        " - " .
                        ($reservation['salle'] ?? "Salle inconnue"),
                    'start' => $reservation['start_date'],
                    'end' => date('Y-m-d', strtotime($reservation['end_date'] . ' +1 day')), // Ajout d'un jour pour l'affichage
                    'salle' => $reservation['salle'] ?? "Salle inconnue",
                    'allDay' => true,
                    'extendedProps' => [
                        'client_name' => $reservation['client_name'] ?? "Client inconnu",
                        'id_client' => $reservation['id_client'] ?? "Client inconnu",
                        'client_prenom' => $reservation['client_prenom'] ?? "Prenom inconnu",
                        'montant_total' => number_format($reservation['montant_total'], 0, ',', ' ') . ' FCFA',
                        'total_services' => number_format($reservation['total_services'], 0, ',', ' ') . ' FCFA',
                        'montant_quitance' => number_format($reservation['montant_quitance'], 0, ',', ' ') . ' FCFA',
                        'montant_payer' => number_format($reservation['montant_payer'], 0, ',', ' ') . ' FCFA',
                        'montant_reduction' => $montant_reduction,
                        'montant_restant' => $reservation['montant_restant'],
                        'montant_quotion' => number_format($reservation['montant_quotion'], 0, ',', ' ') . ' FCFA',
                        'montant_quotion_Input' => $reservation['montant_quotion'],
                        'solde_salle' => number_format($reservation['solde_salle'], 0, ',', ' ') . ' FCFA',
                        'montant_facture_restant' => number_format($reservation['montant_facture_restant'], 0, ',', ' '),
                        'montant_facture_restant_cacher' => $reservation['montant_facture_restant_cacher'],
                        'montant_caution_restant' => number_format($reservation['montant_caution_restant'], 0, ',', ' '),
                        'montant_caution_restant_cacher' => $reservation['montant_caution_restant_cacher'],
                        'statut' => $reservation['statut'] ?? "En attente", // Ajout du statut avec valeur par défaut
                        'statut_valider' => $reservation['statut_valider'],
                        'description' => $reservation['nbr_mouvement'] ?? 'Aucune description',
                        'nbr_mouvement' => $reservation['nbr_mouvement'],
                        'services' => $reservation['services'] ?? [] // Liste des services
                    ],
                    // Ajout du statut sous le titre
                    'display' => 'block', // Assure que l'événement est bien affiché
                    'rendering' => 'background', // Optionnel si besoin d'un fond spécifique
                ];
            });

            // Log::info($formattedEvents);
            return response()->json($formattedEvents, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des événements'], 500);
        }
    }
}
