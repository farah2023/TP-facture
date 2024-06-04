<?php
// Traitement de la consommation
session_start();
include('config.php');
require('fpdf186/fpdf.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $consommation = $_POST["consommation"];
    $annee = $_POST["annee"];
    $mois = $_POST["mois"];
    $photoCompteur = $_POST["photo"]; // À adapter selon votre besoin, généralement ce sera un fichier uploadé

    // Récupérer l'ID du client depuis la session
    if (isset($_SESSION['ID_Client'])) {
        $ID_Client = $_SESSION['ID_Client'];

        // Vérifier si la consommation pour ce mois et cette année existe déjà
        $queryConsommationExistante = "SELECT id_consommation
                                       FROM consommation
                                       WHERE ID_Client = '$ID_Client'
                                       AND Annee = '$annee'
                                       AND Mois = '$mois'";

        $resultConsommationExistante = mysqli_query($connection, $queryConsommationExistante);
        $rowConsommationExistante = mysqli_fetch_assoc($resultConsommationExistante);

        if ($rowConsommationExistante) {
            // Si la consommation existe déjà pour ce mois et cette année, afficher un message d'erreur
            echo "Erreur : la consommation pour le mois $mois/$annee a déjà été saisie.";
        } else {
            // La consommation n'existe pas encore, procéder à l'insertion

            // Vérifier la consommation du mois précédent
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

            if ($rowMoisPrecedent) {

                $consommation_mois_precedent = $rowMoisPrecedent['consommation'];
                // Calculer la différence entre les consommations
                $difference_consommation = $consommation - $consommation_mois_precedent;

                // Vérifier la différence
                if ($difference_consommation < 0 || $difference_consommation > 1000) {
                    // Si la différence est négative ou très grande, afficher un message d'anomalie
                    echo "il ya une anomalie dans la valeur de consommation";
                    // Enregistrez la consommation avec l'état "anomalie" dans la base de données
                    $queryAnomalie = "INSERT INTO consommation ( annee, mois,consommation, ID_Client, image, etat_cons) 
                                      VALUES ('$annee', '$mois','$consommation', '$ID_Client','$photoCompteur', 'anomalie')";

                    if (!mysqli_query($connection, $queryAnomalie)) {
                        echo "Erreur : " . mysqli_error($connection);
                    }

                    header("Location: consommation.php");
                    exit();
                } else {
                    // Si tout est en ordre, continuer le processus d'insertion

                    // Récupérer les informations du client depuis la base de données
                    $queryClient = "SELECT Nom, Prenom, Adresse
                                    FROM client
                                    WHERE ID_Client = '$ID_Client'";

                    $resultClient = mysqli_query($connection, $queryClient);
                    $rowClient = mysqli_fetch_assoc($resultClient);

                    if ($rowClient) {
                        // Les informations du client sont disponibles

                        // Utiliser $rowClient pour accéder aux informations du client

                        // Calculer les prix en fonction de la consommation
                        $prix_unitaire = 0; // Prix unitaire de base

                        if ($difference_consommation > 0 && $difference_consommation <= 100) {
                            $prix_unitaire = 0.8; // 0,8 DH/kwh pour la consommation entre 0 et 100 KWH
                        } elseif ($difference_consommation > 100 && $difference_consommation <= 200) {
                            $prix_unitaire = 0.9; // 0,9 DH/kwh pour la consommation entre 101 et 200 KWH
                        } elseif ($difference_consommation > 201) {
                            $prix_unitaire = 1.0; // 1,0 DH/kwh pour la consommation supérieure à 200 KWH
                        }

                        $prix_ht = $difference_consommation * $prix_unitaire;
                        $tva = 0.14 * $prix_ht;
                        $prix_ttc = $prix_ht + $tva;

                        // Début de la transaction
                        mysqli_autocommit($connection, FALSE);

                        try {
                            // Insérer les données dans la table "consommations"
                            $sqlConsommation = "INSERT INTO consommation ( annee, mois,consommation, ID_Client, image, etat_cons) 
                                                VALUES ('$annee', '$mois','$consommation', '$ID_Client','$photoCompteur', 'verifie')";

                            if (!mysqli_query($connection, $sqlConsommation)) {
                                throw new Exception(mysqli_error($connection));
                            }

                            // Récupérer l'ID de la consommation récemment insérée
                            $id_consommation = mysqli_insert_id($connection);

                            // Insérer les données dans la table "factures"
                            $sqlFacture = "INSERT INTO factures (prix_HT, prix_taxes, prix_TTC, consommation, etat, ID_Client, id_consommation) 
                                        VALUES ('$prix_ht', '$tva', '$prix_ttc', '$difference_consommation', 'non paye', '$ID_Client', '$id_consommation')";

                            if (!mysqli_query($connection, $sqlFacture)) {
                                throw new Exception(mysqli_error($connection));
                            }

                            // Commit de la transaction si tout s'est bien passé
                            mysqli_commit($connection);

                            // Générer la facture si aucune anomalie
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
                            $pdf->Image('uploads/' . $photoCompteur, 20, 25, 150); // Ajout de l'image
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
                            

                            // echo "Consommation et facture enregistrées avec succès.";
                        } catch (Exception $e) {
                            // Rollback en cas d'erreur
                            mysqli_rollback($connection);
                            echo "Erreur lors de l'enregistrement de la consommation et de la facture : " . $e->getMessage();
                        }
                    } else {
                        echo "Erreur : Informations du client non disponibles.";
                    }
                }
            } else {
                // Si la consommation du mois précédent n'est pas disponible, afficher un message d'erreur
                echo "Erreur : la consommation du mois précédent n'est pas disponible.";
            }
        }
    } else {
        echo "ID client non trouvé dans la session.";
    }
} else {
    echo "Méthode de requête non autorisée.";
}

// Fermeture de la connexion à la base de données
mysqli_close($connection);
?>
