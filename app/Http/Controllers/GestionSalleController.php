<?php

namespace App\Http\Controllers;


use App\Models\Salle;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class GestionSalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $entreprise_id = Auth::user()->entreprise_id;
            // Récupération de toutes les service dans la base de données
            $salles = Salle::with('entreprise')
                ->where('entreprise_id', $entreprise_id)
                ->get();
            return view('components.gestion_salles.base_donne_salle', compact('salles'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function index_services(Request $request)
    {
        try {
            $entreprise_id = Auth::user()->entreprise_id;
            // Récupération de toutes les service dans la base de données
            $services = Service::with('entreprise')
                ->where('entreprise_id', $entreprise_id)
                ->get();
            return view('components.gestion_salles.services', compact('services'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function enregistre_service(Request $request)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'tarif' => 'required',
            'description' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'tarif.required' => 'Le tarif est obligatoire.',

            'description.string' => 'La description doit être une chaîne de caractères.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $entreprise_id = Auth::user()->entreprise_id;
            $service = new Service();
            $service->nom = $request->input('nom');
            $service->tarif = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('tarif')));
            $service->description = $request->input('description');
            $service->entreprise_id = $entreprise_id;
            $service->statut = 1;
            // Enregistrement du service dans la base de données
            $service->save();

            return redirect()->back()->with('success', 'Votre service à été enrégistré avec succès !');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }


    }
    public function modifier_service(Request $request, $id)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'tarif' => 'required',
            'description' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'tarif.required' => 'Le tarif est obligatoire.',

            'description.string' => 'La description doit être une chaîne de caractères.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            // Récupération du service à modifier
            $service = Service::findOrFail($id);

            // Mise à jour des attributs du service
            $service->nom = $request->input('nom');
            $service->tarif = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('tarif')));
            $service->description = $request->input('description');

            // Sauvegarde des modifications en base de données
            $service->save();

            return redirect()->back()->with('success', 'Le service a été modifié avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }


    }
    public function supprimer_service($id)
    {
        try {
            // Récupération du service à supprimer
            $service = Service::findOrFail($id);

            // Suppression du service de la base de données
            $service->delete();
            return redirect()->back()->with('success', 'Le service a été supprimé avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'capacite' => 'required',
            'equipements' => 'nullable|string',
            'montant_base' => 'required',
            'caution' => 'required',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'capacite.required' => 'La capacité est obligatoire.',

            'equipements.string' => 'Les équipements doivent être une chaîne de caractères.',

            'montant_base.required' => 'Le montant de base est obligatoire.',

            'caution.required' => 'La caution est obligatoire.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entreprise_id = Auth::user()->entreprise_id;
            $salle = new Salle();
            $salle->nom = $request->input('nom');
            $salle->capacite = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('capacite')));
            $salle->equipements = $request->input('equipements');
            $salle->montant_base = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('montant_base')));
            $salle->caution = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('caution')));
            $salle->entreprise_id = $entreprise_id;
            $salle->statut = 1;
            $salle->save();

            return redirect()->back()->with('success', 'Votre salle a été enrégistré avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'capacite' => 'required',
            'equipements' => 'nullable|string',
            'montant_base' => 'required',
            'caution' => 'required',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'capacite.required' => 'La capacité est obligatoire.',

            'equipements.string' => 'Les équipements doivent être une chaîne de caractères.',

            'montant_base.required' => 'Le montant de base est obligatoire.',

            'caution.required' => 'La caution est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Récupération du service à modifier
            $salle = Salle::findOrFail($id);
            $entreprise_id = Auth::user()->entreprise_id;
            // Mise à jour des attributs
            $salle->nom = $request->input('nom');
            $salle->capacite = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('capacite')));
            $salle->equipements = $request->input('equipements');
            $salle->montant_base = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('montant_base')));
            $salle->caution = str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->input('caution')));
            $salle->entreprise_id = $entreprise_id;

            // Enregistrement en base de données
            $salle->save();

            return redirect()->back()->with('success', 'Votre salle a été modifié avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Récupération du salle à supprimer
            $salle = Salle::findOrFail($id);

            // Suppression du salle
            $salle->delete();

            return redirect()->back()->with('success', 'La salle a été supprimé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



}
