<?php

namespace App\Helpers;

use App\Models\ApprobaCaisse;
use App\Models\ApprobaCaution;
use App\Models\ApprobaEvenement;
use App\Models\RegimeAssurance;
use App\Models\Pays;
use App\Models\Ville;
use App\Models\Beneficiaire;
use App\Models\Permission;
use App\Models\PermissionUser;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;

class DateHelper
{

    public static function approbation_info()
    {

        $approbaEvenement = ApprobaEvenement::where('statut', 'en_attente')->where('entreprise_id', Auth::user()->entreprise_id)->count();
        $ApprobaCaisse = ApprobaCaisse::where('statut', 'en_attente')->where('entreprise_id', Auth::user()->entreprise_id)->count();
        $ApprobaCaution = ApprobaCaution::where('statut', 'en_attente')->where('entreprise_id', Auth::user()->entreprise_id)->count();
        $nombre_approbation = $approbaEvenement + $ApprobaCaisse + $ApprobaCaution;

        $PermissionUsers = PermissionUser::with('permission')
        ->where('user_id', Auth::id())
        ->get();
        // dd($PermissionUsers);

        return [
            'nombre_approbation' => $nombre_approbation,
            'PermissionUsers' => $PermissionUsers,
        ];
    }
    public static function convertirDateFormat($date)
    {
        // Convertir la date du format 'Y-m-d' en objet DateTime
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        if ($dateObj) {
            // Reformater la date en 'd/m/Y'
            return $dateObj->format('d/m/Y');
        } else {
            // Si la date n'est pas valide, retourner null ou un message d'erreur
            return null; // Ou 'Format de date invalide'
        }
    }
    public static function convertirDateExcel($date)
    {
        try {
            // Vérifier si c'est une date brute Excel (numéro)
            if (is_numeric($date)) {
                $dateObject = Date::excelToDateTimeObject($date);
            } else {
                // Sinon, essayer de créer un objet DateTime à partir d'une chaîne
                $dateObject = new DateTime($date);
            }

            // Retourner la date formatée en jj/mm/aaaa
            return $dateObject->format('Y-m-d');
        } catch (Exception $e) {
            // Si une erreur survient, retourner null ou un message d'erreur
            return null;
        }
    }
    public static function convertirDateEnTexte($date)
    {
        // Convertir la date du format 'd/m/Y' en objet DateTime
        $dateObj = DateTime::createFromFormat('d/m/Y', $date);

        if ($dateObj) {
            // Traduction manuelle des jours et des mois
            $jours = ['Sunday' => 'dimanche', 'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi', 'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi'];
            $mois = ['January' => 'janvier', 'February' => 'février', 'March' => 'mars', 'April' => 'avril', 'May' => 'mai', 'June' => 'juin', 'July' => 'juillet', 'August' => 'août', 'September' => 'septembre', 'October' => 'octobre', 'November' => 'novembre', 'December' => 'décembre'];

            // Format de la date en anglais
            $jourEn = $dateObj->format('l');
            $moisEn = $dateObj->format('F');
            $jour = $jours[$jourEn] ?? $jourEn;
            $mois = $mois[$moisEn] ?? $moisEn;

            // Formatter la date en 'lundi 12 février 2024'
            return $jour . ' ' . $dateObj->format('d') . ' ' . $mois . ' ' . $dateObj->format('Y');
        } else {
            // Si la date n'est pas valide, retourner null ou un message d'erreur
            return null;
        }
    }
    public static function formatNumber($number)
    {
        return number_format($number, 0, '.', ' '); // Formate le nombre avec des espaces comme séparateurs de milliers
    }

    public static function determinerTitre($sexe)
    {
        // Normaliser le sexe en minuscule pour une comparaison cohérente
        $sexe = strtolower(trim($sexe ?? ''));

        // Gestion des différents types de codification
        $codificationsFemmes = ['f', 'femme', 'female', 'féminin', 'madame', 'woman', 'mme', 'mm'];
        $codificationsHommes = ['h', 'homme', 'male', 'masculin', 'monsieur', 'man', 'm.', 'mr', 'm'];

        if (in_array($sexe, $codificationsFemmes)) {
            return 'féminin';
        }

        if (in_array($sexe, $codificationsHommes)) {
            return 'masculin';
        }

        // Si le sexe est indéterminé ou non reconnu
        return '';
    }
}
