<?php
// Traitement de la consommation
session_start();
include('config.php');
require('fpdf186/fpdf.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $consommation = $_POST["consommation"];
    $annee = $_POST["annee"];
    $mois = $_POST["mois"];
    $photoCompteur = $_POST["photo"]; 

    if (isset($_SESSION['ID_Client'])) {
        $ID_Client = $_SESSION['ID_Client'];


        $queryConsommationExistante = "SELECT id_consommation
                                       FROM consommation
                                       WHERE ID_Client = '$ID_Client'
                                       AND Annee = '$annee'
                                       AND Mois = '$mois'";

        $resultConsommationExistante = mysqli_query($connection, $queryConsommationExistante);
        $rowConsommationExistante = mysqli_fetch_assoc($resultConsommationExistante);

        if ($rowConsommationExistante) {

            echo "Erreur : la consommation pour le mois $mois/$annee a déjà été saisie.";
        } else {

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
             
                $difference_consommation = $consommation - $consommation_mois_precedent;

        
                if ($difference_consommation < 0 || $difference_consommation > 1000) {
              
                    echo "il ya une anomalie dans la valeur de consommation";
           
                    $queryAnomalie = "INSERT INTO consommation ( annee, mois,consommation, ID_Client, image, etat_cons) 
                                      VALUES ('$annee', '$mois','$consommation', '$ID_Client','$photoCompteur', 'anomalie')";

                    if (!mysqli_query($connection, $queryAnomalie)) {
                        echo "Erreur : " . mysqli_error($connection);
                    }

                    header("Location: consommation.php");
                    exit();
                } else {
              
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

                        // Début de la transaction
                        mysqli_autocommit($connection, FALSE);

                        try {
                     
                            $sqlConsommation = "INSERT INTO consommation ( annee, mois,consommation, ID_Client, image, etat_cons) 
                                                VALUES ('$annee', '$mois','$consommation', '$ID_Client','$photoCompteur', 'verifie')";

                            if (!mysqli_query($connection, $sqlConsommation)) {
                                throw new Exception(mysqli_error($connection));
                            }

                    
                            $id_consommation = mysqli_insert_id($connection);

                            $sqlFacture = "INSERT INTO factures (prix_HT, prix_taxes, prix_TTC, consommation, etat, ID_Client, id_consommation) 
                                        VALUES ('$prix_ht', '$tva', '$prix_ttc', '$difference_consommation', 'non paye', '$ID_Client', '$id_consommation')";

                            if (!mysqli_query($connection, $sqlFacture)) {
                                throw new Exception(mysqli_error($connection));
                            }

                        
                            mysqli_commit($connection);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);


$pdf->Image('uploads/' . $photoCompteur, 125, 20, 80);
// Entête de la facture
$pdf->SetFillColor(200, 220, 255); // Couleur de fond
$pdf->Cell(0, 10, 'e-Facture', 0, 1, 'C', true); // Nom de votre entreprise
$pdf->Cell(0, 10, 'Facture de la periode: 0' . $mois . ' /' . $annee, 0, 1, 'C'); // Mois et année de consommation
$pdf->Cell(0, 10, 'Usage : BT Domestique', 0, 1, 'C'); // Type d'usage

$pdf->Ln(45); // Saut de ligne

// Informations sur le client
$pdf->SetFillColor(230, 230, 230); // Couleur de fond
$pdf->Cell(0, 10, 'Informations sur le client', 0, 1, 'C', true); // Titre
$pdf->Cell(0, 10, 'Nom : ' . $rowClient['Nom'], 0, 1, 'L', true);
$pdf->Cell(0, 10, 'Prenom: ' . $rowClient['Prenom'], 0, 1, 'L', true);
$pdf->Cell(0, 10, 'Adresse : ' . $rowClient['Adresse'], 0, 1, 'L', true);
$pdf->Ln(15); // Saut de ligne

// Informations sur la consommation
$pdf->SetFillColor(200, 220, 255); // Couleur de fond
$pdf->Cell(0, 10, 'Informations sur la consommation', 0, 1, 'C', true); // Titre
$pdf->Cell(0, 10, 'Consommation: ' .  $difference_consommation . ' kWh', 0, 1, 'L', true);
$pdf->Cell(0, 10, 'Prix HT: ' . $prix_ht . ' DH', 0, 1, 'L', true);
$pdf->Cell(0, 10, 'Prix Taxes: ' . $tva . ' DH', 0, 1, 'L', true);
$pdf->Cell(0, 10, 'Total TTC: ' . $prix_ttc . ' DH', 0, 1, 'L', true);
$pdf->Cell(0, 10, 'Date limite de paiement:01/ '.$mois+1 .'/'.$annee, 0, 1, 'C'); // Ajouter la date limite de paiement ici




                            // Envoyer le PDF en sortie
                            ob_clean();  // Ajout de cette ligne pour nettoyer le tampon de sortie
                            header('Content-Type: application/pdf');
                            header('Content-Disposition: attachment; filename="Facture.pdf"');
                            $pdf->Output('D', 'e-Facture.pdf');
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
}

// Fermeture de la connexion à la base de données
mysqli_close($connection);
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <title>Consommation</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Comic+Neue%3A700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400%2C600%2C700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter%3A700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400%2C600" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto%3A400%2C700" />
  <link rel="stylesheet" href="../styles/client.css" />
</head>
<body>
  <div class="item-1-v8n">
    <div class="auto-group-6vze-f6N">
      <p class="e-facture-y74">E-facture</p>
      <div class="group-4-48W">
        <div class="rectangle-1-moc">
        </div>
        <a href="reclamation.php" class="reclamation-VUi">Reclamation</a>
      </div>
      <div class="group-4-48W">
        <div class="rectangle-1-moc">
        </div>
        <a href="dashbord.php" class="reclamation-VUi">Factures</a>
      </div>
   
    </div>
    <div class="auto-group-cktt-XTL">
      <div class="auto-group-d3pt-enr">
        <div class="links-yKL">
          <a href="Acceuil.php">Home</a>
          <a href="Acceuil.php">About</a>
          <a href="Acceuil.php">Services</a>
          <a href="Acceuil.php">Contact</a>
        </div>
        <div class="group-6-sp2">
          <div class="rectangle-1-EPg">
          </div>
          <a  href ="Acceuil.php" class="log-out-Zwk">log out</a>
        </div>
      </div>
      
      <form class="frame-1-sSe" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="input-o5Q">
          <div class="label-KpS">Mois</div>
          <div >
            <div class="inputinnercontainer-ira">

            <select name="mois" id="mois" class="inputcontainer-cYe">
                                    <option value="0">-- Selectionner le mois --</option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Fevrier</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Aout</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Decembre</option>
                                </select>

            </div>
          </div>
        </div>
        <div class="input-o5Q">
          <div class="label-KpS">Annee</div>
          <div >
            <div class="inputinnercontainer-ira">
              <input type="number" name="annee" class="inputcontainer-cYe">
            </div>
          </div>
        </div>
        
        <div class="input-o5Q">
          <div class="label-KpS">Consommation</div>
          <div >
            <div class="inputinnercontainer-ira">
              <input type="text" name="consommation" class="inputcontainer-cYe">
            </div>
          </div>
        </div>
        <br><br><br><br>
        <div class="input-o5Q">
          <div class="label-KpS">compteur</div>
          <div >
            <div class="inputinnercontainer-ira">
              <input type="file" name="photo" class="inputcontainer-cYe" accept=".jpg,.jpeg,.png" required>
            </div>
          </div>
        </div>
          <input type="submit" value="Envoyer" name="submit">
        <div class="auto-group-p4yc-V7t">
         
        </div>
</form>
    </div>
  </div>
</body>
</html>
