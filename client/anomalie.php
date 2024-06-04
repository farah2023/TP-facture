<?php
// Traitement de la consommation
session_start();
include('config.php');
require('fpdf186/fpdf.php');

if (isset($_SESSION['ID_Client'])) {

    $ID_Client = $_SESSION['ID_Client'];
    $mois = $_GET['mois'];
    $annee = $_GET['annee'];
    $consommation = $_GET['consommation'];
    $statut = $_GET['statut'];
    $montant = $_GET['montant'];

    $mois_precedent = $mois - 1;
    if ($mois_precedent == 0) {
        $mois_precedent = 12;
        $annee_precedente = $annee - 1;
    } else {
        $annee_precedente = $annee;
    }

    $queryMoisPrecedent = "SELECT consommation
                           FROM consommation
                           WHERE ID_Client = '$ID_Client'
                           AND Annee = '$annee_precedente'
                           AND Mois = '$mois_precedent'";

    $resultMoisPrecedent = mysqli_query($connection, $queryMoisPrecedent);
    $rowMoisPrecedent = mysqli_fetch_assoc($resultMoisPrecedent);
    $consommation_mois_precedent = $rowMoisPrecedent['consommation'];
    $difference_consommation = $consommation - $consommation_mois_precedent;

    $queryClient = "SELECT Nom, Prenom, Adresse
                    FROM client
                    WHERE ID_Client = '$ID_Client'";

    $resultClient = mysqli_query($connection, $queryClient);
    $rowClient = mysqli_fetch_assoc($resultClient);

    if ($rowClient) {

        $prix_unitaire = 0;

        if ($difference_consommation > 0 && $difference_consommation <= 100) {
            $prix_unitaire = 0.8;
        } elseif ($difference_consommation > 100 && $difference_consommation <= 200) {
            $prix_unitaire = 0.9;
        } elseif ($difference_consommation > 201) {
            $prix_unitaire = 1.0;
        }

        $prix_ht = $difference_consommation * $prix_unitaire;
        $tva = 0.14 * $prix_ht;
        $prix_ttc = $prix_ht + $tva;

        mysqli_autocommit($connection, FALSE);

        try {
            // Check if the record already exists in the factures table
            $queryCheckFacture = "SELECT * FROM factures WHERE ID_Client = '$ID_Client' AND prix_TTC = '$montant' AND consommation = '$difference_consommation'";
            $resultCheckFacture = mysqli_query($connection, $queryCheckFacture);

            $queryMoisPrecedent = "SELECT id_consommation
            FROM consommation
            WHERE ID_Client = '$ID_Client'
            AND Annee = '$annee'
            AND Mois = '$mois'";

           $resultIdClient = mysqli_query($connection, $queryMoisPrecedent);
           $rowIdClient = mysqli_fetch_assoc($resultIdClient);
           $id_consommation = $rowIdClient['id_consommation'];

           


            if (mysqli_num_rows($resultCheckFacture) == 0) {
                // Insert into the factures table if the record doesn't exist
                $sqlFacture = "INSERT INTO factures (prix_HT, prix_taxes, prix_TTC, consommation, etat, ID_Client, id_consommation) 
                            VALUES ('$prix_ht', '$tva', '$prix_ttc', '$difference_consommation', 'non paye', '$ID_Client', '$id_consommation')";
                if (!mysqli_query($connection, $sqlFacture)) {
                    throw new Exception(mysqli_error($connection));
                }
            } else {
                throw new Exception("Facture already exists for this client");
            }

            mysqli_commit($connection);

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12); // Définir la police
            
            // Entête de la facture
            $pdf->SetFillColor(200, 220, 255); // Couleur de fond
            $pdf->Cell(0, 10, 'e-Facture', 0, 1, 'C', true); // Nom de votre entreprise
            $pdf->Cell(0, 10, 'Facture de la periode: ' . $mois . ' / ' . $annee, 0, 1, 'C'); // Mois et année de consommation
            $pdf->Cell(0, 10, 'Usage : BT Domestique', 0, 1, 'C'); // Type d'usage
            
            $pdf->Ln(10); // Saut de ligne
            
            // Affichage de l'image du compteur
            $pdf->Image('uploads/image.jpg' , 20, 25, 150); // Ajout de l'image
            $pdf->Ln(20);
            // Informations sur le client et la consommation
            $pdf->SetFillColor(230, 230, 230); // Couleur de fond
            $pdf->Cell(95, 10, 'Informations sur le client', 1, 0, 'C', true); // Bloc d'informations sur le client
            $pdf->Cell(95, 10, 'Informations sur la consommation', 1, 1, 'C', true); // Bloc d'informations sur la consommation
            
            $pdf->Cell(95, 10, 'Nom du client: ' . $rowClient['Nom'], 1, 0, 'L', true);
            $pdf->Cell(95, 10, 'Consommation: ' .  $difference_consommation . ' kWh', 1, 1, 'L', true);
            $pdf->Cell(95, 10, 'Prenom du client: ' . $rowClient['Prenom'], 1, 0, 'L', true);
            $pdf->Cell(95, 10, 'Prix HT: ' . $prix_ht . ' DH', 1, 1, 'L', true);
            $pdf->Cell(95, 10, 'Adresse du client: ' . $rowClient['Adresse'], 1, 0, 'L', true);
            $pdf->Cell(95, 10, 'Prix Taxes: ' . $tva . ' DH', 1, 1, 'L', true);
            $pdf->Cell(95, 10, '', 1, 0, 'C', true); // Cellule vide pour aligner les blocs
            $pdf->Cell(95, 10, 'Total TTC: ' . $prix_ttc . ' DH', 1, 1, 'L', true);
            
            // Date limite de paiement
            $pdf->Cell(190, 10, 'Date limite de paiement: 01/ ' . ($mois + 1) . ' / ' . $annee, 1, 1, 'C'); // Ajouter la date limite de paiement ici
            
            // Envoyer le PDF en sortie
            ob_clean();  // Nettoyer le tampon de sortie
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Facture.pdf"');
            $pdf->Output('D', 'Facture.pdf');
            exit();
            

        } catch (Exception $e) {
            // Rollback in case of error
            mysqli_rollback($connection);
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Client information not available.";
    }
    
    
}

// Close the database connection
mysqli_close($connection);
?>
