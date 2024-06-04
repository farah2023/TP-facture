<?php
include('../client/config.php');

$file = 'consommation_annuelle.txt';

// Read the file line by line
$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Process each line
foreach ($lines as $line) {
    // Split the line into ID_Client, Annee, and Consommation
    $data = explode(',', $line);
    
    // Check if the line has the correct number of elements
    if (count($data) === 3) {
        // Extract the values
        $id_client = trim($data[0]);
        $annee = trim($data[1]);
        $consommation = trim($data[2]);

        // Insert data into the database
        $sql = "INSERT INTO consommation_annuelle (ID_Client,ID_fr, Consommation, Annee) VALUES ('$id_client', 1,'$consommation', '$annee')";

        if ($connection->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    } else {
        echo "Error: Invalid data format";
    }
}

// Close the database connection
$connection->close();
?>