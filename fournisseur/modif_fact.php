<?php
include '../client/config.php';

// Check if the necessary query parameters are set
if(isset($_GET['mois']) && isset($_GET['annee'])) {
    $mois = $_GET['mois'];
    $annee = $_GET['annee'];

    echo $mois;
    echo $annee;

    // Your code for processing the form submission goes here
    if(isset($_POST['submit'])) {
        $consommation_compteur = mysqli_real_escape_string($connection, $_POST['consommation_compteur']);
        $statut = mysqli_real_escape_string($connection, $_POST['statut']);
        $clientid = mysqli_real_escape_string($connection, $_POST['clientid']);

        // Update the database with the provided values
        $update_query = "UPDATE `consommation` SET consommation = '$consommation_compteur', etat_cons = '$statut' WHERE ID_Client = '$clientid' AND Mois = '$mois' AND Annee = '$annee'";
        $update_result = mysqli_query($connection, $update_query);

        if($update_result) {
            // Redirect back to factures.php after successful update
            header('Location: factures.php');
            exit(); // Ensure script execution stops after redirection
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
} else {
    echo "Missing required parameters mois and annee";
}
?>

