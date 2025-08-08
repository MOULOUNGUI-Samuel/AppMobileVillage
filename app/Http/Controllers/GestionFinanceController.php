<?php

namespace App\Http\Controllers;

use App\Models\ApprobaEvenement;
use App\Models\Caisse;
use App\Models\Caution;
use App\Models\Client;
use App\Models\MouvementCaisse;
use App\Models\NatureMouvement;
use App\Models\Reservation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GestionFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {
            $entreprise_id = Auth::user()->entreprise_id;

            // 1. On charge toutes les caisses ET leurs mouvements associés en une seule fois
            // C'est la méthode la plus performante (Eager Loading)
            $caisses = Caisse::with(['user', 'mouvements' => function ($query) {
                // On trie les mouvements de chaque caisse par date
                $query->orderBy('created_at', 'desc');
            }])
                ->where('entreprise_id', $entreprise_id)
                ->get();

            // 2. On crée un "dictionnaire" de mouvements pour le JavaScript.
            // La clé sera l'ID de la caisse, et la valeur sera la liste de ses mouvements formatés.
            $mouvementsByCaisse = [];
            foreach ($caisses as $caisse) {
                $mouvementsByCaisse[$caisse->id] = $caisse->mouvements->map(function ($mouvement) {
                    return [
                        'id' => $mouvement->id, // <--- AJOUTER CETTE LIGNE
                        'motif' =>  Str::limit($mouvement->nature_mouvement , 12, '...') ?? 'Transaction',
                        'type' => $mouvement->type_mouvement,
                        'formatted_date' => $mouvement->created_at->translatedFormat('d F Y à H:i'),
                        'formatted_amount' => ($mouvement->type_mouvement == 'ENTREE' ? '+' : '-') . ' ' . number_format($mouvement->montant, 0, ',', ' '),
                        'css_class' => $mouvement->type_mouvement == 'ENTREE' ? 'positive' : 'negative',
                        'icon' => $mouvement->type_mouvement == 'ENTREE' ? 'ph-bold ph-arrow-down' : 'ph-bold ph-arrow-up',
                        'icon_bg_class' => $mouvement->type_mouvement == 'ENTREE' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger',
                    ];
                });
            }

            // On envoie les deux variables à la vue
            return view('components.finances.caisses', compact('caisses', 'mouvementsByCaisse'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            // 'solde.required' => 'Le solde est obligatoire.',
        ]);

        $user = User::where('nom', $request->input('nom_gerant'))->first();


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $entreprise_id = Auth::user()->entreprise_id;
        try {
            $caisse = new Caisse();
            $caisse->nom = $request->input('nom');
            // Suppression des espaces pour le champ solde
            // $caisse->solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));
            $caisse->statut_principale = 0;
            $caisse->statut_caisse = 'active';
            $caisse->user_id = $user->id;
            $caisse->entreprise_id = $entreprise_id;

            $caisse->save();

            return redirect()->back()->with('success', 'Votre caisse a été enregistrée avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function enregistre_nature(Request $request)
    {
        //
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'acteur' => 'required',
            'type' => 'required',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'acteur.required' => 'L\'acteur est obligatoire.',
            'type.required' => 'Le type est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entreprise_id = Auth::user()->entreprise_id;
            $caisse = new NatureMouvement();
            $caisse->nom = $request->input('nom');
            $caisse->acteur = $request->input('acteur');
            $caisse->type = $request->input('type');
            $caisse->statut = 'active';
            $caisse->entreprise_id = $entreprise_id;

            $caisse->save();

            return redirect()->back()->with('success', 'Votre caisse a été enregistrée avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function caissesInfo(Request $request)
    {
        $caisseId = $request->query('caisse_id');

        // Log de l'ID de la caisse sélectionnée
        // Log::info('ID de la caisse sélectionnée : ' . $caisseId);

        $caisses = Caisse::with('Entreprise')->where('id', $caisseId)->get();

        return response()->json([
            'success' => true,
            'caisse_id' => $caisseId,
            'caisses' => $caisses->map(function ($item) {
                return [
                    'nom' => $item->nom,
                    'solde' => $item->solde,
                ];
            }),
        ]);
    }
    public function caissesInfo_mouve(Request $request)
    {
        $caisseNom = $request->query('caisseNom');

        $natures = NatureMouvement::with('Entreprise')
            ->where('type', $caisseNom)
            ->get();

        return response()->json([
            'success' => true,
            'caisses' => $natures->map(function ($item) {
                return [
                    'nom' => $item->nom,
                    'acteur' => $item->acteur,
                    'id' => $item->id,
                ];
            }),
        ]);
    }


    public function transfert_fonds(Request $request)
    {
        //
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'solde' => 'required',
            'id' => 'required',
        ], [
            'solde.required' => 'Le solde est obligatoire.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $caisse_gagne = Caisse::where('nom', $request->input('nom'))->first();
        $caisse_pert = Caisse::where('id', $request->input('id'))->first();

        $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));
        $nouveau_montant_agmenter = intval($solde) + intval($caisse_gagne->solde);

        $nouveau_montant_diminu = $caisse_pert->solde - $solde;
        $user_id = Auth::user()->id;
        // Création d'une mouvement caisse pour la caisse gagnante
        $nature_mouvement = 'Fonds réçu de la caisse [' . $caisse_pert->nom . ']';
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'Trans_' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->caisse_id = $caisse_gagne->id;
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->nature_mouvement = $nature_mouvement;
        $MouvementCaisse_gagne->type_mouvement = 'ENTREE';
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $caisse_gagne->solde;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_agmenter;
        $MouvementCaisse_gagne->description = $request->input('description');
        $MouvementCaisse_gagne->save();

        // Création d'une mouvement caisse pour la caisse perdante
        $nature_mouvement = 'Transfert de fonds vers la caisse [' . $caisse_gagne->nom . ']';
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'Trans_' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->caisse_id = $caisse_pert->id;
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->nature_mouvement = $nature_mouvement;
        $MouvementCaisse_gagne->type_mouvement = 'SORTIE';
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $caisse_pert->solde;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_diminu;
        $MouvementCaisse_gagne->description = $request->input('description');
        $MouvementCaisse_gagne->save();

        $caisse_gagne_fond = Caisse::findOrFail($caisse_gagne->id);
        $caisse_gagne_fond->solde = $nouveau_montant_agmenter;
        $caisse_gagne_fond->save();

        $caisse_pert_fond = Caisse::findOrFail($request->input('id'));
        $caisse_pert_fond->solde = $nouveau_montant_diminu;
        $caisse_pert_fond->save();



        return redirect()->back()->with('success', 'Transfert de fonds effectué avec succès !');
    }
    public function enregistrer_mouvement_client(Request $request)
    {
        //

        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'solde' => 'required',
            'nom_caisse' => 'required',
            'type' => 'required',
            'nature' => 'required',
            'client_id' => 'required',
        ], [
            'solde.required' => 'Le solde est obligatoire.',
            'client_id.required' => 'Identifiant du client est obligatoire.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $compte_client = Client::where('id', $request->input('client_id'))->first();
        $texteSansMontant = preg_replace('/\s*\(\d+\)/', '', $request->input('nom_caisse'));
        $caisse = Caisse::where('nom', $texteSansMontant)->first();
        $user_id = Auth::user()->id;
        $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));
        // dd($request->input('nom_caisse'));
        if ($request->input('type') == 'ENTREE') {
            $nouveau_montant = intval($solde) + intval($caisse->solde);
            $nouveau_montant_client = intval($solde) + intval($compte_client->solde);
            $ancien_montant_client = $compte_client->solde;
        } else {
            $nouveau_montant = intval($caisse->solde) - intval($solde);
            $nouveau_montant_client = intval($compte_client->solde) - intval($solde);
            $ancien_montant_client = $compte_client->solde;
        }
        if ($request->input('type') == 'ENTREE') {
            $nature_sum_solde = 'solde_entre';
            $type_operation = "Approvisionnement";
            $action_compte = "Entrer";
        } else {
            $nature_sum_solde = 'solde_sort';
            $type_operation = "Décaissement";
            $action_compte = "Sortie";
        }
        // Création d'une mouvement client
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'Approv_' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->caisse_id = $caisse->id;
        $MouvementCaisse_gagne->client_id = $request->input('client_id');
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->nature_mouvement = $request->input('nature');
        $MouvementCaisse_gagne->type_mouvement = $request->input('type');
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $compte_client->solde;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_client;
        $MouvementCaisse_gagne->sum_solde = $nature_sum_solde;
        $MouvementCaisse_gagne->description = $action_compte . " du compte";
        $MouvementCaisse_gagne->save();

        // Création d'une mouvement caisse
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'Approv_' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->caisse_id = $caisse->id;
        $MouvementCaisse_gagne->client_id = $request->input('client_id');
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->nature_mouvement = $request->input('nature');
        $MouvementCaisse_gagne->type_mouvement = $request->input('type');
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $caisse->solde;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant;
        $MouvementCaisse_gagne->sum_solde = "";
        $MouvementCaisse_gagne->description = $type_operation . " du compte client " . $compte_client->nom . " " . $compte_client->prenom;
        $MouvementCaisse_gagne->save();

        $caisse_recharge = Caisse::findOrFail($caisse->id);
        $caisse_recharge->solde = $nouveau_montant;
        $caisse_recharge->save();

        $caisse_recharge = Client::findOrFail($request->input('client_id'));
        $caisse_recharge->solde = $nouveau_montant_client;
        $caisse_recharge->ancien_solde = $ancien_montant_client;
        $caisse_recharge->save();

        return redirect()->back()->with('success', 'Mouvement de caisse enrégistré avec succès !');
    }
    public function enregistrer_mouvement(Request $request)
    {
        //
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'solde' => 'required',
            'id' => 'required',
            'type' => 'required',
            'nature' => 'required',
        ], [
            'solde.required' => 'Le solde est obligatoire.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $caisse = Caisse::where('id', $request->input('id'))->first();
        $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));
        if ($solde < 1) {
            return redirect()->back()->withErrors("Veuillez renseigner un montant superieur à 0")->withInput();
        }
        if ($request->input('type') == 'ENTREE') {
            $nouveau_montant = intval($solde) + intval($caisse->solde);
        } else {
            $nouveau_montant = intval($caisse->solde) - intval($solde);
        }

        $user_id = Auth::user()->id;
        // Création d'une mouvement caisse
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'Trans_' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->caisse_id = $request->input('id');
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->nature_mouvement = $request->input('nature');
        $MouvementCaisse_gagne->type_mouvement = $request->input('type');
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $caisse->solde;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant;
        $MouvementCaisse_gagne->description = $request->input('description');
        $MouvementCaisse_gagne->save();

        $caisse_recharge = Caisse::findOrFail($request->input('id'));
        $caisse_recharge->solde = $nouveau_montant;
        $caisse_recharge->save();

        return redirect()->back()->with('success', 'Mouvement de caisse enrégistré avec succès !');
    }
    public function Solde_guitance(Request $request)
    {
        // dd($request->description);
        $client = Client::where('id', $request->input('client_id'))->first();
        $reservation = Reservation::where('id', $request->input('reservation_id'))->first();

        $montant_quitance_reserve = intval($reservation->montant_quitance - $reservation->montant_payer);
        $user_id = Auth::user()->id;


        if ($request->input('paiement_direct')) {


            if ($request->solde) {

                $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));
                $nouveau_montant_payer = intval($reservation->montant_payer) + intval($solde);
                $caisse_recharge = Caisse::where('statut_principale', 1)->first();


                // Création d'une mouvement caisse client
                $MouvementCaisse_gagne = new MouvementCaisse();
                $MouvementCaisse_gagne->ref_mouvement = 'FACT-' . date('Y-m-d H:i:s');
                $MouvementCaisse_gagne->caisse_id = $caisse_recharge->id;
                $MouvementCaisse_gagne->client_id = $request->input('client_id');
                $MouvementCaisse_gagne->user_id = $user_id;
                $MouvementCaisse_gagne->reservation_id = $request->input('reservation_id');
                $MouvementCaisse_gagne->nature_mouvement = 'Paiement de la facture';
                $MouvementCaisse_gagne->type_mouvement = 'ENTREE';
                $MouvementCaisse_gagne->mode_paiement = 'espèces';
                $MouvementCaisse_gagne->montant = $solde;
                $MouvementCaisse_gagne->montant_base = $caisse_recharge->solde;
                $MouvementCaisse_gagne->nouveau_montant =  $caisse_recharge->solde + $solde;
                $MouvementCaisse_gagne->description = 'Règlement de la facture pour la réservation ' . $reservation->ref_quitance;
                $MouvementCaisse_gagne->save();

                $caisse_recharge1 = Caisse::findOrFail($caisse_recharge->id);
                $caisse_recharge1->solde = $caisse_recharge->solde + $solde;
                $caisse_recharge1->save();


                $reservation_recharge = Reservation::findOrFail($request->input('reservation_id'));
                $reservation_recharge->montant_payer = $nouveau_montant_payer;
                $reservation_recharge->statut = 'En cours';
                $reservation_recharge->save();
            }

            if ($request->solde2) {
                $reservation2 = Reservation::where('id', $request->input('reservation_id'))->first();
                $solde2 = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde2')));

                $caution = Caution::where('reservation_id', $reservation2->id)->first();

                // $nouveau_montant_payer2 = intval($reservation2->montant_payer) + intval($solde2);

                $caisse_recharge2 = Caisse::where('statut_principale', 1)
                    ->where('entreprise_id', Auth::user()->entreprise_id)
                    ->first();



                // Création d'une mouvement caisse client
                $MouvementCaisse_gagne = new MouvementCaisse();
                $MouvementCaisse_gagne->ref_mouvement = 'CAUT-' . date('Y-m-d H:i:s');
                $MouvementCaisse_gagne->caisse_id = $caisse_recharge2->id;
                $MouvementCaisse_gagne->client_id = $request->input('client_id');
                $MouvementCaisse_gagne->caution_id = $caution->id;
                $MouvementCaisse_gagne->user_id = $user_id;
                $MouvementCaisse_gagne->reservation_id = $request->input('reservation_id');
                $MouvementCaisse_gagne->nature_mouvement = 'Paiement de la facture';
                $MouvementCaisse_gagne->type_mouvement = $request->description ? 'SORTIE' : 'ENTREE';
                $MouvementCaisse_gagne->mode_paiement = 'espèces';
                $MouvementCaisse_gagne->montant = $solde2;
                $MouvementCaisse_gagne->montant_base = $caisse_recharge2->solde;
                $MouvementCaisse_gagne->nouveau_montant = $request->description ? $caisse_recharge2->solde - $solde2 : $caisse_recharge2->solde + $solde2;
                if ($request->description) {
                    $MouvementCaisse_gagne->description = $request->description;
                } else {
                    $MouvementCaisse_gagne->description = 'Règlement de la caution pour la réservation ' . $reservation2->ref_quitance;
                }
                $MouvementCaisse_gagne->save();
                $MouvementCaisse = MouvementCaisse::findOrFail($MouvementCaisse_gagne->id);
                $MouvementCaisse->created_at = now()->addMinutes(2);
                $MouvementCaisse->updated_at = now()->addMinutes(2);
                $MouvementCaisse->save();


                $nouveau_montant_payer_caution = intval($caution->montant_rembourse) + intval($solde2);

                $caution_recharge = Caution::findOrFail($caution->id);
                $caution_recharge->montant_rembourse = $nouveau_montant_payer_caution;
                $caution_recharge->statut = 'confirmée';
                $caution_recharge->save();

                $caisse_recharge_caisse = Caisse::findOrFail($caisse_recharge2->id);
                $caisse_recharge_caisse->solde = $request->description ? $caisse_recharge2->solde - $solde2 : $caisse_recharge2->solde + $solde2;
                $caisse_recharge_caisse->save();

                // $caisse_recharge_reservation = Reservation::findOrFail($request->input('reservation_id'));
                // $caisse_recharge_reservation->montant_payer = $nouveau_montant_payer2;
                // $caisse_recharge_reservation->statut = 'En cours';
                // $caisse_recharge_reservation->save();
            }
        } else {
            $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));

            $nouveau_montant_payer = intval($reservation->montant_payer) + intval($solde);
            $nouveau_montant_client = intval($client->solde) - intval($solde);
            // Création d'une mouvement caisse reservation
            $MouvementCaisse_gagne = new MouvementCaisse();
            $MouvementCaisse_gagne->ref_mouvement = 'FACT-' . date('Y-m-d H:i:s');
            $MouvementCaisse_gagne->client_id = $request->input('client_id');
            $MouvementCaisse_gagne->user_id = $user_id;
            $MouvementCaisse_gagne->reservation_id = $request->input('reservation_id');
            $MouvementCaisse_gagne->nature_mouvement = 'Paiement de la facture';
            $MouvementCaisse_gagne->type_mouvement = 'ENTREE';
            $MouvementCaisse_gagne->mode_paiement = 'espèces';
            $MouvementCaisse_gagne->montant = $solde;
            $MouvementCaisse_gagne->montant_base = $montant_quitance_reserve;
            $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_payer;
            $MouvementCaisse_gagne->description = 'Paie de la facture pour la réservation ' . $reservation->ref_quitance;
            $MouvementCaisse_gagne->save();
            // Création d'une mouvement caisse client
            $MouvementCaisse_gagne = new MouvementCaisse();
            $MouvementCaisse_gagne->ref_mouvement = 'FACT-' . date('Y-m-d H:i:s');
            $MouvementCaisse_gagne->client_id = $request->input('client_id');
            $MouvementCaisse_gagne->user_id = $user_id;
            $MouvementCaisse_gagne->reservation_id = $request->input('reservation_id');
            $MouvementCaisse_gagne->nature_mouvement = 'Paiement de la facture';
            $MouvementCaisse_gagne->type_mouvement = 'SORTIE';
            $MouvementCaisse_gagne->mode_paiement = 'espèces';
            $MouvementCaisse_gagne->montant = $solde;
            $MouvementCaisse_gagne->montant_base = ($client->solde < 0) ? 0 : $client->solde;
            $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_client;
            $MouvementCaisse_gagne->sum_solde = 'solde_sort';
            $MouvementCaisse_gagne->description = 'Paie de la facture pour la réservation ' . $reservation->ref_quitance;
            $MouvementCaisse_gagne->save();
            $caisse_recharge = Client::findOrFail($request->input('client_id'));
            $caisse_recharge->solde = $nouveau_montant_client;
            $caisse_recharge->save();

            $caisse_recharge = Reservation::findOrFail($request->input('reservation_id'));
            $caisse_recharge->montant_payer = $nouveau_montant_payer;
            $caisse_recharge->statut = 'En cours';
            $caisse_recharge->save();
        }

        return redirect()->back()->with('success', 'Règlement effectué avec succès !');
    }
    public function Solde_caution(Request $request)
    {
        //
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'solde' => 'required',
        ], [
            'solde.required' => 'Le solde est obligatoire.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $client = Client::where('id', $request->input('client_id'))->first();
        $caution = Caution::where('reservation_id', $request->input('reservation_id'))->first();
        $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('solde')));

        $nouveau_montant_payer = intval($caution->montant_rembourse) + intval($solde);
        $nouveau_montant_client = intval($client->solde) - intval($solde);
        $user_id = Auth::user()->id;
        // Création d'une mouvement caisse caution
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'CAUT-' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->client_id = $request->input('client_id');
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->reservation_id = $request->input('reservation_id');
        $MouvementCaisse_gagne->caution_id = $request->input('caution_id');
        $MouvementCaisse_gagne->nature_mouvement = 'Paiement de la caution';
        $MouvementCaisse_gagne->type_mouvement = 'ENTREE';
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $caution->montant_rembourse;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_payer;
        $MouvementCaisse_gagne->description = $request->input('description');
        $MouvementCaisse_gagne->save();

        // Création d'une mouvement caisse client
        $MouvementCaisse_gagne = new MouvementCaisse();
        $MouvementCaisse_gagne->ref_mouvement = 'FACT-' . date('Y-m-d H:i:s');
        $MouvementCaisse_gagne->client_id = $request->input('client_id');
        $MouvementCaisse_gagne->reservation_id = $request->input('reservation_id');
        $MouvementCaisse_gagne->caution_id = $request->input('caution_id');
        $MouvementCaisse_gagne->user_id = $user_id;
        $MouvementCaisse_gagne->nature_mouvement = 'Paiement de la caution';
        $MouvementCaisse_gagne->type_mouvement = 'SORTIE';
        $MouvementCaisse_gagne->mode_paiement = 'espèces';
        $MouvementCaisse_gagne->montant = $solde;
        $MouvementCaisse_gagne->montant_base = $client->solde;
        $MouvementCaisse_gagne->nouveau_montant = $nouveau_montant_client;
        $MouvementCaisse_gagne->sum_solde = 'solde_sort';
        $MouvementCaisse_gagne->description = $request->input('description');
        $MouvementCaisse_gagne->save();

        $caisse_recharge = Client::findOrFail($request->input('client_id'));
        $caisse_recharge->solde = $nouveau_montant_client;
        $caisse_recharge->save();

        $caisse_recharge = Caution::findOrFail($request->input('caution_id'));
        $caisse_recharge->montant_rembourse = $nouveau_montant_payer;
        $caisse_recharge->statut = 'En cours';
        $caisse_recharge->save();

        return redirect()->back()->with('success', 'Règlement effectué avec succès !');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'nom_gerant' => 'required',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
        ]);
        $entreprise_id = Auth::user()->entreprise_id;
        $users = User::with('entreprise')
            ->where('entreprise_id', $entreprise_id)
            ->where('nom', $request->input('nom_gerant'))
            ->first();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $caisse = Caisse::findOrFail($id);
            $caisse->nom = $request->input('nom');
            $caisse->user_id = $users->id;
            $caisse->save();

            return redirect()->back()->with('success', 'Votre Caisse a été modifié avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function compte_client(string $id)
    {
        //
        $clients = Client::findOrFail($id);
        $caisses = Caisse::All();
        $reservations = Reservation::where('client_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $cautions = Caution::with('reservation')
            ->whereHas('reservation', fn($query) => $query->where('client_id', $id))
            ->orderBy('created_at', 'desc')
            ->get();
        $mouvementCaisses_client = MouvementCaisse::with('reservation', 'user')
            ->where('client_id', $id)
            ->orderBy('created_at', 'desc') // Trie par 'created_at' de manière décroissante
            ->get();

        // Somme des montants pour les mouvements d'entrée
        $mouvementCaisses_client_entrant = MouvementCaisse::where('client_id', $id)
            ->where('sum_solde', 'solde_entre')
            ->where('description', 'Entrer du compte')
            ->sum('montant');
        // Somme des montants pour les mouvements de sortie
        $mouvementCaisses_client_sortant = MouvementCaisse::where('client_id', $id)
            ->where('sum_solde', 'solde_sort')
            // ->where('description', 'Entrer du compte')
            ->sum('montant');

        return view('components.finances.compte_client', compact('clients', 'mouvementCaisses_client_sortant', 'mouvementCaisses_client_entrant', 'caisses', 'mouvementCaisses_client', 'cautions', 'reservations'));
    }
    public function guitance_client(string $id)
    {
        //
        $reservations_client = Reservation::with('client')->where('id', $id)->first();
        $mouvementCaisses_client = MouvementCaisse::with(['reservation.user'])
            ->where('reservation_id', $reservations_client->id)
            ->orderBy('created_at', 'desc')
            ->get();



        return view('components.finances.derails_guitance', compact('reservations_client', 'mouvementCaisses_client'));
    }
    public function cotion_client(string $id)
    {
        //
        $caution_client = Caution::with(['reservation.client'])->where('id', $id)->first();


        $mouvementCaisses_client = MouvementCaisse::with(['caution.reservation.user'])
            ->where('caution_id', $caution_client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('components.finances.derails_caution', compact('caution_client', 'mouvementCaisses_client'));
    }
    public function destroy_nature(string $id)
    {
        //
        try {
            $nature = NatureMouvement::findOrFail($id);
            $nature->delete();

            return redirect()->back()->with('success', 'Nature de mouvement supprimé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function parametre_caisse(Request $request, $id)
    {
        //
        try {

            $solde = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('seuil_caisse')));
            $caisse = Caisse::findOrFail($id);
            $caisse->seuil_caisse = $solde;
            $caisse->save();

            return redirect()->back()->with('success', 'Seuil défini avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
