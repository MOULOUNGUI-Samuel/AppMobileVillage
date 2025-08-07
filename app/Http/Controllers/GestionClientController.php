<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\ReservationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GestionClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //liste des clients
        try {
            // Récupération de toutes les service dans la base de données
            $entreprise_id = Auth::user()->entreprise_id;
            $clients = Client::where('entreprise_id', $entreprise_id)->get();

            // On déclare un tableau vide
            $data_reservations = [];

            // Pour chaque client
            foreach ($clients as $client) {
                // On récupère les réservations du client en filtrant par son ID
                $entreprise_id = Auth::user()->entreprise_id;
                // On récupère les réservations du client en filtrant par son ID
                $reservations = Reservation::with(['client', 'salle', 'reservationServices.service'])
                    ->where('client_id', $client->id)
                    ->whereHas('salle', function ($query) use ($entreprise_id) {
                        $query->where('entreprise_id', $entreprise_id);
                    })
                    ->orderBy('start_date', 'desc') // Tri décroissant
                    ->get();



                // Pour chaque réservation du client, on l'ajoute au tableau
                foreach ($reservations as $reservation) {
                    $data_reservations[] = (object) [
                        'reservation' => $reservation,
                        'reservationServices' => $reservation->reservationServices, // déjà chargé avec le service
                    ];
                }
            }

            return view('components.gestion_clients.base_donne_client', compact('clients', 'data_reservations'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'observation' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',

            'email.nullable' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',

            'adresse.nullable' => 'L\'adresse est obligatoire.',
            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entreprise_id = Auth::user()->entreprise_id;
            $client = new Client();
            $client->entreprise_id = $entreprise_id;
            $client->nom = $request->input('nom');
            $client->prenom = $request->input('prenom');
            $client->email = $request->input('email');
            $client->telephone = $request->input('telephone');
            $client->adresse = $request->input('adresse');
            $client->observation = $request->input('observation');
            $client->save();

            return redirect()->back()->with('success', 'Votre client a été enregistré avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . $id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'observation' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',

            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',

            'adresse.required' => 'L\'adresse est obligatoire.',
            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $client = Client::findOrFail($id);
            $client->nom = $request->input('nom');
            $client->prenom = $request->input('prenom');
            $client->email = $request->input('email');
            $client->telephone = $request->input('telephone');
            $client->adresse = $request->input('adresse');
            $client->observation = $request->input('observation');
            $client->save();

            return redirect()->back()->with('success', 'Votre client a été modifié avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return redirect()->back()->with('success', 'Votre client a été supprimé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
