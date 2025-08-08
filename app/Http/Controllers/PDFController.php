<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Caution;
use App\Models\MouvementCaisse;
use App\Models\Quittance;
use App\Models\Client;
use App\Models\ReservationService;
use Illuminate\Support\Facades\Auth;


class PDFController extends Controller
{
    /**
     * Générer la quittance en PDF
     */
    public function facture_directe($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $reservation = Reservation::with('user', 'client', 'salle')->where('id', $id)->first();
        $ReservationServices = ReservationService::with('reservation', 'service')->where('reservation_id', $id)->get();
        $caution = Caution::where('reservation_id', $id)->first();
        $pdf = new \FPDF();
        for ($j = 1; $j <= 2; $j++) {
            $pdf->AddPage();
            // LOGO
           $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, mb_convert_encoding('FACTURE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
            $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($reservation->ref_quitance, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            $pdf->Ln(10);

            // INFORMATIONS ENTREPRISE
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
            $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
            $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
            $pdf->Ln(5);

            // INFORMATIONS CLIENT
            // $pdf->SetFont('Arial', '', 10);
            // $pdf->Text(140, 125, mb_convert_encoding("Remis par: " . ucfirst(strtolower(Auth::user()->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($client->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
            // $pdf->Ln(5);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Nom du client :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->nom . " " . $reservation->client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Adresse :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->adresse, 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Téléphone :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->telephone, 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Statut de la reservation :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            if ($reservation->statut == 'en attente') {
                $pdf->Cell(140, 5, mb_convert_encoding('IMPAYER', 'ISO-8859-1', 'UTF-8'), 0, 1);
            } elseif ($reservation->montant_quitance - $reservation->montant_payer == 0) {
                $pdf->Cell(140, 5, mb_convert_encoding('COMPLET', 'ISO-8859-1', 'UTF-8'), 0, 1);
            } else {
                $pdf->Cell(140, 5, mb_convert_encoding($reservation->statut, 'ISO-8859-1', 'UTF-8'), 0, 1);
            }


            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);

            // Largeur des colonnes
            $w1 = 60;
            $w2 = 70;
            $w3 = 60;

            // Hauteur d'une ligne
            $h = 8;

            // En-têtes
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($w1, 10, mb_convert_encoding('', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
            $pdf->Cell($w2, 10, mb_convert_encoding('DESIGNATION', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
            $pdf->Cell($w3, 10, mb_convert_encoding('PRIX', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');

            // Contenu
            if (!$ReservationServices->isEmpty()) {
                $pdf->SetFont('Arial', 'B', 11);
                // Nombre de lignes dans ReservationServices
                $nbServices = count($ReservationServices);
                // Ligne 1 & 2 : fusion verticale sur $w1 pour "Services"
                $pdf->MultiCell($w1, $h * $nbServices, mb_convert_encoding('Services', 'ISO-8859-1', 'UTF-8'), 1, 'C', false);
                $x = $pdf->GetX();
                $y = $pdf->GetY() - ($h * $nbServices); // revenir au point de départ ligne 1

                $pdf->SetFont('Arial', '', 11);
                // Position du curseur après la cellule fusionnée "Services"

                // Affichage des lignes à droite
                $index = 0;

                foreach ($ReservationServices as $reservation_service) {
                    if ($index === 0) {
                        // ✅ PREMIÈRE LIGNE
                        $pdf->SetXY($x + $w1, $y);
                    } else {
                        $pdf->SetX($x + $w1);
                    }
                    $pdf->Cell($w2, $h, mb_convert_encoding($reservation_service->service->nom, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
                    $pdf->Cell($w3, $h, number_format($reservation_service->prix_unitaire, 0, ',', ' ') . ' FCFA', 1, 1, 'C');

                    $index++;
                }
            } else {
                $nbServices = 1;
            }
            $pdf->SetFont('Arial', '', 12);

            $dateDebut = new \DateTime($reservation->start_date);
            $dateFin = new \DateTime($reservation->end_date);

            $interval = $dateDebut->diff($dateFin);
            $nbJours = $interval->days + 1; // Intervalle inclusif

            // Lignes 5 à 8
            $index1 = 0;
            for ($i = ($nbServices + 1); $i <= ($nbServices + 3); $i++) {
                // if($index1 ===)
                if ($index1 === 0) {
                    // ✅ Fusion C1 + C2
                    $pdf->Cell($w1 + $w2, $h, mb_convert_encoding("Montant de la caution", 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
                    $pdf->Cell($w3, $h, number_format($caution->montant_caution, 0, ',', ' ') . ' FCFA', 1, 1, 'C');
                } elseif ($index1 === 1) {
                    // Lignes normales
                    $pdf->Cell($w1 + $w2, $h, mb_convert_encoding("Montant de la réduction", 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
                    if ($reservation->montant_reduction > 0) {
                        $pdf->Cell($w3, $h, number_format($reservation->montant_reduction, 0, ',', ' ') . ' FCFA', 1, 1, 'C');
                    } else {
                        $pdf->Cell($w3, $h, number_format($reservation->montant_reduction, 0, ',', ' '), 1, 1, 'C');
                    }
                } else {
                    $pdf->SetFont('Arial', '', 11);
                    // Lignes normales
                    $pdf->Cell($w1 + $w2, $h, mb_convert_encoding("Durée de l'évenement ", 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
                    $pdf->Cell($w3, $h, mb_convert_encoding($nbJours . " jour" . ($nbJours > 1 ? 's' : ''), 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
                    // Lignes normales
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->Cell($w1, $h, mb_convert_encoding("Salle", 'ISO-8859-1', 'UTF-8'), 1, 0, "C");
                    $pdf->SetFont('Arial', '', 11);
                    $pdf->Cell($w2, $h, mb_convert_encoding($reservation->salle->nom, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
                    $pdf->Cell($w3, $h, number_format($reservation->salle->montant_base, 0, ',', ' ') . ' FCFA', 1, 1, 'C');
                }
                $index1++;
            }

            // Ligne 8 : TOTAL
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell($w1 + $w2, 10, mb_convert_encoding('Totaux', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell($w3, 10, mb_convert_encoding(number_format($reservation->montant_total, 0, ',', ' ') . ' FCFA', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 11);
            $pdf->Ln(10);
            // Utilise le namespace global pour éviter l’erreur
            $formatter = new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT);

            $montantEnLettres = $formatter->format($reservation->montant_total);
            $pdf->Cell(200, 5,  mb_convert_encoding("Arrêter la présente facture à la somme de : " . ucfirst($montantEnLettres), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

            $pdf->Ln(10);
            $pdf->Cell(200, 5,  mb_convert_encoding("Service : " . Auth::user()->role_user, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            $pdf->Ln(15);
            $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        }
        $pdf->Output();
        exit;
    }
    public function releve_client($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $reservation = Reservation::with('user', 'client')->findOrFail($id);
        $client = $reservation->client;
        $mouvements = MouvementCaisse::with(['reservation.user'])->where('reservation_id', $id)->get();

        $pdf = new \FPDF('L', 'mm', 'A4'); // 'L' = Landscape
        for ($i = 1; $i <= 2; $i++) {
            $pdf->AddPage();

            // LOGO & TITRE
            $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(280, 10, mb_convert_encoding('RELEVER DU CLIENT', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(280, 5, mb_convert_encoding('Date : ' . now()->format('d/m/Y H:i'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            $pdf->Cell(280, 5, mb_convert_encoding('Référence : ' . $reservation->ref_quitance, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            $pdf->Ln(5);

            // INFOS ENTREPRISE
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1);
            $pdf->Cell(100, 5, mb_convert_encoding("Tel: 074085110 / 066762032", 'ISO-8859-1', 'UTF-8'), 0, 1);
            $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1);
            $pdf->Ln(5);

            // INFOS CLIENT
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Nom du client :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 5, mb_convert_encoding($client->nom . ' ' . $client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Adresse :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 5, mb_convert_encoding($client->adresse, 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Téléphone :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 5, mb_convert_encoding($client->telephone, 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, mb_convert_encoding("Statut de la réservation :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $statut = $reservation->statut == 'en attente'
                ? 'IMPAYER'
                : (($reservation->montant_quitance - $reservation->montant_payer == 0) ? 'COMPLET' : $reservation->statut);
            $pdf->Cell(140, 5, mb_convert_encoding(strtoupper($statut), 'ISO-8859-1', 'UTF-8'), 0, 1);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 6, mb_convert_encoding("Date de réservation :", 'ISO-8859-1', 'UTF-8'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(140, 6, mb_convert_encoding($reservation->created_at->format('d/m/Y H:i'), 'ISO-8859-1', 'UTF-8'), 0, 1);

            // MOUVEMENTS (exemples, remplace-les si tu as une table mouvements liée à $reservation)
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, mb_convert_encoding('Référence', 'ISO-8859-1', 'UTF-8'), 1);
            $pdf->Cell(137, 8, mb_convert_encoding('Description', 'ISO-8859-1', 'UTF-8'), 1);
            $pdf->Cell(35, 8, mb_convert_encoding('Entrée', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
            $pdf->Cell(35, 8, mb_convert_encoding('Sortie', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 10);

            $totalEntree = 0;
            $totalSortie = 0;

            if ($mouvements === null) {
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(277, 8, mb_convert_encoding("Aucun mouvement enregistré.", 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
            } else {
                foreach ($mouvements as $mouvement) {
                    $pdf->Cell(70, 8, mb_convert_encoding($mouvement->ref_mouvement, 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(137, 8, mb_convert_encoding($mouvement->description, 'ISO-8859-1', 'UTF-8'), 1);

                    if ($mouvement->type_mouvement === 'ENTREE') {
                        $pdf->Cell(35, 8, number_format((float)$mouvement->montant, 0, ',', ' '), 1, 0, 'C');
                        $pdf->Cell(35, 8, '', 1, 1, 'C');
                        $totalEntree += $mouvement->montant;
                    } else {
                        $pdf->Cell(35, 8, '', 1, 0, 'C');
                        $pdf->Cell(35, 8, number_format((float)$mouvement->montant, 0, ',', ' '), 1, 1, 'C');
                        $totalSortie += $mouvement->montant;
                    }
                }

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(207, 8, mb_convert_encoding('Totaux', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');
                $pdf->Cell(35, 8, number_format($totalEntree, 0, ',', ' '), 1, 0, 'C');
                $pdf->Cell(35, 8, number_format($totalSortie, 0, ',', ' '), 1, 1, 'C');
            }

            $pdf->SetFont('Arial', '', 10);
            // Bas de page
            $pdf->Ln(5);
            $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1);

            $pdf->Ln(1);
            $pdf->Cell(250, 5, mb_convert_encoding("Service : " . Auth::user()->role_user, 'ISO-8859-1', 'UTF-8'), 0, 0, "C");
        }

        $pdf->Output();
        exit;
    }


    public function quittance($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $reservation = Reservation::with('user', 'client')->where('id', $id)->first();

        $pdf = new \FPDF();
        $pdf->AddPage();

        // LOGO
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('FACTURE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($reservation->ref_quitance, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(10);

        // INFORMATIONS ENTREPRISE
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        // INFORMATIONS CLIENT
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(140, 125, mb_convert_encoding("Remis par: " . ucfirst(strtolower(Auth::user()->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($client->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Nom du client :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->nom . " " . $reservation->client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Adresse :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->adresse, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Téléphone :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->telephone, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Statut de la reservation :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        if ($reservation->statut == 'en attente') {
            $pdf->Cell(140, 5, mb_convert_encoding('IMPAYER', 'ISO-8859-1', 'UTF-8'), 0, 1);
        } elseif ($reservation->montant_quitance - $reservation->montant_payer == 0) {
            $pdf->Cell(140, 5, mb_convert_encoding('COMPLET', 'ISO-8859-1', 'UTF-8'), 0, 1);
        } else {
            $pdf->Cell(140, 5, mb_convert_encoding($reservation->statut, 'ISO-8859-1', 'UTF-8'), 0, 1);
        }


        $pdf->Ln(10);
        // TABLEAU DES MONTANTS
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Montant à payer', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Montant restant', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Montant payé', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', false);


        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(63.33, 7, number_format($reservation->montant_quitance, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Ancien montant
        $pdf->Cell(63.33, 7, number_format($reservation->montant_quitance - $reservation->montant_payer, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Nouveau montant
        $pdf->Cell(63.33, 7, number_format($reservation->montant_payer, 0, ',', ' ') . ' FCFA', 1, 1, 'C'); // Montant FCFA


        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // APPROBATION
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        $pdf = new \FPDF();
        $pdf->AddPage();

        // LOGO
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('FACTURE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($reservation->ref_quitance, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(10);

        // INFORMATIONS ENTREPRISE
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        // INFORMATIONS CLIENT
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(140, 125, mb_convert_encoding("Remis par: " . ucfirst(strtolower(Auth::user()->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($client->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Nom du client :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->nom . " " . $reservation->client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Adresse :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->adresse, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Téléphone :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($reservation->client->telephone, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Statut de la reservation :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        if ($reservation->statut == 'en attente') {
            $pdf->Cell(140, 5, mb_convert_encoding('IMPAYER', 'ISO-8859-1', 'UTF-8'), 0, 1);
        } elseif ($reservation->montant_quitance - $reservation->montant_payer == 0) {
            $pdf->Cell(140, 5, mb_convert_encoding('COMPLET', 'ISO-8859-1', 'UTF-8'), 0, 1);
        } else {
            $pdf->Cell(140, 5, mb_convert_encoding($reservation->statut, 'ISO-8859-1', 'UTF-8'), 0, 1);
        }


        $pdf->Ln(10);
        // TABLEAU DES MONTANTS
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Montant à payer', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Montant restant', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Montant payé', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', false);


        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(63.33, 7, number_format($reservation->montant_quitance, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Ancien montant
        $pdf->Cell(63.33, 7, number_format($reservation->montant_quitance - $reservation->montant_payer, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Nouveau montant
        $pdf->Cell(63.33, 7, number_format($reservation->montant_payer, 0, ',', ' ') . ' FCFA', 1, 1, 'C'); // Montant FCFA


        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // APPROBATION
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        // SORTIE PDF
        $pdf->Output("I", "Mouvements_Caisse_" . $id . ".pdf");
        exit;
    }


    public function caution($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $reservation = Reservation::with('client')->find($id);
        if (!$reservation) {
            return abort(404, "Réservation non trouvée !");
        }

        $client = $reservation->client;
        $cautions = Caution::where('reservation_id', $reservation->id)->get();

        $pdf = new \FPDF();
        $pdf->AddPage();

        // Logo
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);

        // Titre et Date
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('REÇU DE CAUTION', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Ln(10);

        // Informations générales
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        // Client
        $pdf->Cell(100, 5, mb_convert_encoding("Client: " . $client->nom . " " . $client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Référence: " . $reservation->ref_quitance, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        if ($cautions->isEmpty()) {
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(190, 10, mb_convert_encoding("Aucune caution enregistrée.", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        } else {
            // Tableau
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(63, 7, mb_convert_encoding('Référence', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
            $pdf->Cell(63, 7, mb_convert_encoding('Montant (FCFA)', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
            $pdf->Cell(64, 7, mb_convert_encoding('Date', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');

            foreach ($cautions as $caution) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(63, 7, mb_convert_encoding($caution->ref_caution, 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
                $pdf->Cell(63, 7, number_format($caution->montant, 0, ',', ' '), 1, 0, 'R');
                $pdf->Cell(64, 7, date('d/m/Y', strtotime($caution->date_caution)), 1, 1, 'C');
            }
        }

        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // Validation
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        // Sortie PDF
        $pdf->Output("I", "Caution_" . $reservation->id . ".pdf");
        exit;
    }



    /**
     * Générer les mouvements de caisse en PDF
     */
    public function mouvementCaisse_client($id, $client_id)
    {
        $user = Auth::user();
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        // Récupérer les informations du client et du mouvement
        $client = Client::findOrFail($client_id);
        $mouvements = MouvementCaisse::with(['reservation.user'])->where('id', $id)->first();


        // Initialiser le PDF
        $pdf = new \FPDF();
        $pdf->AddPage();

        // LOGO
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('MOUVEMENT DE CAISSE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($mouvements->ref_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(10);

        // INFORMATIONS ENTREPRISE
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);



        // Type de Mouvement
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Nom client :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($client->nom . ' ' . $client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1);

        // Nature de Mouvement
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Téléphone :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($client->telephone, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(10);



        // TABLEAU DES MOUVEMENTS
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 7, mb_convert_encoding('Nature de Mouvement', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(50, 7, mb_convert_encoding('Type', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(45, 7, mb_convert_encoding('Montant (FCFA)', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');


        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(95, 7, mb_convert_encoding(ucfirst(strtolower($mouvements->nature_mouvement)), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(50, 7, mb_convert_encoding($mouvements->type_mouvement, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(45, 7, number_format($mouvements->montant, 0, ',', ' '), 1, 1, 'C');


        $pdf->Ln(10);
        // Objet
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Objet:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(25, 107.5, mb_convert_encoding($mouvements->description, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(5);

        // INFORMATIONS CLIENT
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(130, 127, mb_convert_encoding("Remis par : " . ucfirst(strtolower($user->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($user->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // APPROBATION
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        $pdf->AddPage();

        // LOGO
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('MOUVEMENT DE CAISSE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($mouvements->ref_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(10);

        // INFORMATIONS ENTREPRISE
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);



        // Type de Mouvement
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Nom client :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($client->nom . ' ' . $client->prenom, 'ISO-8859-1', 'UTF-8'), 0, 1);

        // Nature de Mouvement
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Téléphone :", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($client->telephone, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(10);



        // TABLEAU DES MOUVEMENTS
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 7, mb_convert_encoding('Nature de Mouvement', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(50, 7, mb_convert_encoding('Type', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(45, 7, mb_convert_encoding('Montant (FCFA)', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');


        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(95, 7, mb_convert_encoding(ucfirst(strtolower($mouvements->nature_mouvement)), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(50, 7, mb_convert_encoding($mouvements->type_mouvement, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(45, 7, number_format($mouvements->montant, 0, ',', ' '), 1, 1, 'C');


        $pdf->Ln(10);
        // Objet
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Objet:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(25, 107.5, mb_convert_encoding($mouvements->description, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(5);

        // INFORMATIONS CLIENT
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(130, 127, mb_convert_encoding("Remis par : " . ucfirst(strtolower($user->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($user->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // APPROBATION
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        // SORTIE PDF
        $pdf->Output("I", "Mouvements_Caisse_" . $id . ".pdf");
        exit;
    }
    public function mouvementCaisse($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $mouvement = MouvementCaisse::where('id', $id)->first();

        $pdf = new \FPDF();
        $pdf->AddPage();

        // LOGO
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('MOUVEMENT DE CAISSE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($mouvement->ref_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(10);

        // INFORMATIONS ENTREPRISE
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        // INFORMATIONS CLIENT
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Remis par: " . ucfirst(strtolower($user = Auth::user()->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($client->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Type de Mouvement:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($mouvement->type_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Nature de Mouvement:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($mouvement->nature_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Objet:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($mouvement->description, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(10);
        // TABLEAU DES MONTANTS
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Ancien Montant en caisse', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Nouveau Montant en caisse', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        if ($mouvement->type_mouvement == "ENTREE") {
            $pdf->Cell(63.33, 10, mb_convert_encoding('Montant versé ', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', false);
        } else {
            $pdf->Cell(63.33, 10, mb_convert_encoding('Montant sortie ', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', false);
        }

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(63.33, 7, number_format($mouvement->montant_base, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Ancien montant
        $pdf->Cell(63.33, 7, number_format($mouvement->nouveau_montant, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Nouveau montant
        if ($mouvement->type_mouvement == "ENTREE") {
            $pdf->Cell(63.33, 7, number_format($mouvement->montant, 0, ',', ' ') . ' FCFA', 1, 1, 'C'); // Montant FCFA
        } else {
            $pdf->Cell(63.33, 7, '- ' . number_format($mouvement->montant, 0, ',', ' ') . ' FCFA', 1, 1, 'C'); // Montant FCFA
        }

        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // APPROBATION
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        // Ajouter une seconde page identique
        $pdf->AddPage();
        // LOGO
       $pdf->Image(public_path('assets/img/logo1.png'), 10, 6, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, mb_convert_encoding('MOUVEMENT DE CAISSE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(190, 5, 'Date: ' . now()->format('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Cell(190, 5, 'Reference : ' . mb_convert_encoding($mouvement->ref_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(10);

        // INFORMATIONS ENTREPRISE
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Adresse: Haut de Gué-Gué", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Cell(100, 5, "Tel: 074085110 / 066762032", 0, 1, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Site événementiel", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        // INFORMATIONS CLIENT
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, mb_convert_encoding("Remis par: " . ucfirst(strtolower($user = Auth::user()->nom ?? 'Inconnu')) . " " . ucfirst(strtolower($client->prenom ?? '')), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Type de Mouvement:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($mouvement->type_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Nature de Mouvement:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($mouvement->nature_mouvement, 'ISO-8859-1', 'UTF-8'), 0, 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 5, mb_convert_encoding("Objet:", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(140, 5, mb_convert_encoding($mouvement->description, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(10);
        // TABLEAU DES MONTANTS
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Ancien Montant en caisse', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        $pdf->Cell(63.33, 10, mb_convert_encoding('Nouveau Montant en caisse', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', false);
        if ($mouvement->type_mouvement == "ENTREE") {
            $pdf->Cell(63.33, 10, mb_convert_encoding('Montant versé ', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', false);
        } else {
            $pdf->Cell(63.33, 10, mb_convert_encoding('Montant sortie ', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', false);
        }

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(63.33, 7, number_format($mouvement->montant_base, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Ancien montant
        $pdf->Cell(63.33, 7, number_format($mouvement->nouveau_montant, 0, ',', ' ') . ' FCFA', 1, 0, 'C'); // Nouveau montant
        if ($mouvement->type_mouvement == "ENTREE") {
            $pdf->Cell(63.33, 7, number_format($mouvement->montant, 0, ',', ' ') . ' FCFA', 1, 1, 'C'); // Montant FCFA
        } else {
            $pdf->Cell(63.33, 7, '- ' . number_format($mouvement->montant, 0, ',', ' ') . ' FCFA', 1, 1, 'C'); // Montant FCFA
        }

        $pdf->Ln(10);
        $pdf->Cell(190, 5, mb_convert_encoding("Fait à Libreville, le " . now()->format('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(10);

        // APPROBATION
        $pdf->Cell(90, 5, mb_convert_encoding("Approuvé par :", 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(100, 5, mb_convert_encoding("Approbation du responsable :", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

        // SORTIE PDF
        $pdf->Output("I", "Mouvements_Caisse_" . $id . ".pdf");
        exit;
    }
}
