<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\Entreprise;
use App\Models\NatureMouvement;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GroupePermission;
use App\Models\Permission;
use App\Models\PermissionUser;
use Illuminate\Support\Facades\Validator;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            // Récupération de toutes les entreprises dans la base de données
            $entreprises = Entreprise::all();
            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return view('components.entreprises.liste_entreprises', compact('entreprises'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function paramettre()
    {
        //
        try {
            $GroupePermissions = GroupePermission::orderBy('created_at', 'desc')->get();
            $Tab_permissions = [];

            foreach ($GroupePermissions as $GroupePermission) {
                $Tab_permissions[] = [
                    'GroupePermission' => $GroupePermission,
                    'Permissions' => Permission::where('groupe_id', $GroupePermission->id)->get(),
                    'PermissionUsers' => PermissionUser::whereIn('permission_id', Permission::where('groupe_id', $GroupePermission->id)->pluck('id'))->get()
                ];
            }
            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return view('components.entreprises.paramettre', compact('Tab_permissions'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function permissions(Request $request)
    {
        //
        try {
            if ($request->nom) {
                $groupe = new GroupePermission();
                $groupe->nom = $request->nom;
                $groupe->description = $request->description;
                $groupe->save();
            }

            foreach ($request->libelles as $libelle) {
                $permission = new Permission();
                $permission->libelle = $libelle;
                $permission->groupe_id = $groupe->id ?? $request->id_hidden;
                $permission->save();
            }
            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return redirect()->back()->with('success', 'Permissions enregistrées avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function groupe_permissions(Request $request, $id)
    {
        //
        try {

            $GroupePermission = GroupePermission::findOrFail($id);
            $GroupePermission->nom = $request->nom;
            $GroupePermission->save();

            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return redirect()->back()->with('success', 'Groupe modifiée avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function modif_permissions(Request $request, $id)
    {
        //
        try {

            $permission = Permission::findOrFail($id);
            $permission->libelle = $request->libelle;
            $permission->save();

            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return redirect()->back()->with('success', 'Permission modifiée avec succès.');
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
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'nom_entreprise' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20', // ici on attend une chaîne pour le numéro de téléphone
            'email' => 'required|email|max:255|unique:users,email',
            'email_entreprise' => 'required|email|max:255|unique:entreprises,email',
        ], [
            'telephone.numeric' => 'Le champ numéro de téléphone doit être un nombre.', // Si vous souhaitez forcer un numérique, adaptez la règle
            'nom.required' => 'Le nom est obligatoire.',
            'nom_entreprise.required' => 'Le nom de l`\'entreprise est obligatoire.',
            'email.required' => 'L\'email du responsable est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
            'email_entreprise.required' => 'L\'email de l\'entreprise est obligatoire.',
            'email_entreprise.unique' => 'Cet email est déjà utilisé par une autre entreprise .',
        ]);

        // Si la validation échoue, on redirige avec les erreurs
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Création de l'entreprise
            $entreprise = new Entreprise();
            $entreprise->nom = $request->nom_entreprise;
            $entreprise->adresse = $request->adresse;
            $entreprise->telephone = $request->telephone;
            $entreprise->email = $request->email_entreprise;
            $entreprise->statut = 'active';
            $entreprise->save();

            $entreprise_id = $entreprise->id;

            // Création de l'utilisateur associé à l'entreprise
            $user = new User();
            // Utilisation de "nom" conformément au modèle User
            $user->nom = $request->nom;
            $user->email = $request->email;
            $user->entreprise_id = $entreprise_id;
            $user->role_user = 'Admin';
            $user->password = Hash::make('123456');
            $user->save();

            // Création d'une caisse pour l'entreprise
            $caisse = new Caisse();
            $caisse->nom = 'Caisse principale';
            $caisse->entreprise_id = $entreprise_id;
            $caisse->user_id = $user->id;
            $caisse->statut_caisse = 'active';
            $caisse->save();


            $caisse_entre = new NatureMouvement();
            $caisse_entre->nom = "Recharger le compte";
            $caisse_entre->acteur = "Client";
            $caisse_entre->type = 'ENTREE';
            $caisse_entre->statut = 'active';
            $caisse_entre->entreprise_id = $entreprise_id;
            $caisse_entre->save();

            $caisse_sort = new NatureMouvement();
            $caisse_sort->nom = 'Retirer du compte';
            $caisse_sort->acteur = "Client";
            $caisse_sort->type = 'SORTIE';
            $caisse_sort->statut = 'active';
            $caisse_sort->entreprise_id = $entreprise_id;
            $caisse_sort->save();

            $caisse_entrep = new NatureMouvement();
            $caisse_entrep->nom = "Approvisionnement";
            $caisse_entrep->acteur = "Entreprise";
            $caisse_entrep->type = 'ENTREE';
            $caisse_entrep->statut = 'active';
            $caisse_entrep->entreprise_id = $entreprise_id;
            $caisse_entrep->save();

            $caisse_sortp = new NatureMouvement();
            $caisse_sortp->nom = 'Décaissement';
            $caisse_sortp->acteur = "Entreprise";
            $caisse_sortp->type = 'SORTIE';
            $caisse_sortp->statut = 'active';
            $caisse_sortp->entreprise_id = $entreprise_id;
            $caisse_sortp->save();

            return redirect()->back()->with('success', 'Entreprise enregistrée avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, on redirige avec le message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
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
    public function supprimer_groupe(string $id)
    {
        //
        try {
            // Recherche du groupe de permissions par son ID
            $groupe = GroupePermission::findOrFail($id);

            // Suppression des permissions associées à ce groupe
            Permission::where('groupe_id', $groupe->id)->delete();

            // Suppression du groupe de permissions
            $groupe->delete();

            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'Groupe supprimé avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function supprimer_permission(string $id)
    {
        //
        try {
            // Recherche du groupe de permissions par son ID
            $groupe = Permission::findOrFail($id);
            // Suppression du groupe de permissions
            $groupe->delete();
            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'Permission supprimée avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function destroy(string $id)
    {
        //
    }
}
