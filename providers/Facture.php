<?php
namespace App\Providers;

use FPDF;

class Facture {
    static private function t($texte){ //convertit le texte en format windows-1252 parce que FPDF ne gere pas UTF-8
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $texte);
    }

    static public function generer($commande, $client, $lignes, $total){ //Construit le PDF, l'enregistre sur le server et l'envoi au navigateur
        $pdf = new FPDF();
        $pdf->AddPage();

        //Section Titre
        $pdf->SetFont('Arial', 'B', 18); 
        $pdf->Cell(0, 12, self::t('Librairie - Facture # '.$commande['id']), 0, 1);

        //Info sur le client
        $pdf->SetFont('Arial', '', 12); 
        $pdf->Cell(0, 8, self::t('Client : '.$client['name']), 0, 1);
        $pdf->Cell(0, 8, 'Date : '.$commande['date_commande'], 0, 1);
        $pdf->ln(6);

        //En-tete
        $pdf->SetFont('Arial', 'B', 12); //
        $pdf->Cell(90, 8, 'Titre', 1);
        $pdf->Cell(30, 8, 'Prix', 1);
        $pdf->Cell(30, 8, self::t('Quantité'), 1);
        $pdf->Cell(40, 8, 'Sous-total', 1, 1);

        //Genere une ligne par entree de livre dans la commande
        $pdf->SetFont('Arial', '', 12);
        foreach($lignes as $ligne){
            $pdf->Cell(90, 8, self::t($ligne['titre']), 1);
            $pdf->Cell(30, 8, number_format($ligne['prix'], 2).' $', 1);
            $pdf->Cell(30, 8, $ligne['quantite'], 1);
            $pdf->Cell(40, 8, number_format($ligne['sous_total'], 2).' $', 1, 1);
        }

        //Total de la commande
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(150, 10, 'Total', 1);
        $pdf->Cell(40, 10, number_format($total, 2).' $', 1, 1);

        //Enregistre le PDF dans le dossier "factures" (et le cree si le dossier n'existe pas) 
        $dossier = 'factures';
        if(!is_dir($dossier)){
            mkdir($dossier);
        }
        $pdf->Output('F', $dossier.'/facture_'.$commande['id'].'.pdf');

        //Affiche le pdf dans le navigateur
        $pdf->Output('I', 'facture_'.$commande['id'].'.pdf');

    }
}