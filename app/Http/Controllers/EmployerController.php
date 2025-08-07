<?php

namespace App\Http\Controllers;

use App\Models\GroupePermission;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $entreprise_id = Auth::user()->entreprise_id;
        $employers = User::where('entreprise_id', $entreprise_id)->get();
        $GroupePermissions = GroupePermission::orderBy('created_at', 'desc')->get();
        $Tab_permissions = [];

        foreach ($GroupePermissions as $GroupePermission) {
            $Tab_permissions[] = [
                'GroupePermission' => $GroupePermission,
                'Permissions' => Permission::where('groupe_id', $GroupePermission->id)->get(),
                'PermissionUsers' => PermissionUser::whereIn('permission_id', Permission::where('groupe_id', $GroupePermission->id)->pluck('id'))->get()
            ];
        }

        return view('components.utilisateurs.liste_employers', compact('employers', 'Tab_permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // Validation des champs du formulaire
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role_user' => 'required',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'string' => 'La valeur doit être une chaîne de caractères',
            'min:6' => 'La chaîne doit avoir une longueur minimale de 6 caractères',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'role_user.required' => 'Le rôle est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entreprise_id = Auth::user()->entreprise_id; // Récupération de l'entreprise de l'utilisateur connecté

            // Création de l'employé
            $user = new User();
            $user->entreprise_id = $entreprise_id;
            $user->nom = $request->input('nom');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password')); // Hashage du mot de passe
            $user->role_user = $request->input('role_user');
            $user->save();

            return redirect()->back()->with('success', 'L\'Utilisateur a été enregistré avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function permission(Request $request)
    {
        // 1. Supprimer toutes les permissions existantes de l'utilisateur
        PermissionUser::where('user_id', $request->user_id)->delete();

        // 2. Réinsérer uniquement les permissions cochées
        if ($request->has('permission')) {
            foreach ($request->permission as $permission_id) {
                PermissionUser::create([
                    'user_id' => $request->user_id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Permissions attribuées avec succès !');
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
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id, // Ignorer l'utilisateur actuel
            'password' => 'nullable|string|min:6', // Optionnel lors de la mise à jour
            'role_user' => 'required|',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'role_user.required' => 'Le rôle est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Récupérer l'utilisateur à modifier
            $user = User::findOrFail($id);
            $user->nom = $request->input('nom');
            $user->email = $request->input('email');

            // Mise à jour du mot de passe uniquement si un nouveau est fourni
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->role_user = $request->input('role_user');
            $user->save();

            return redirect()->back()->with('success', 'L\'Utilisateur a été mis à jour avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'L\'Utilisateur a été supprimé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
