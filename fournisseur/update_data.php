<?php
include '../client/config.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON data sent via AJAX
    $data = json_decode(file_get_contents("php://input"));

    // Extract client ID and new values from the decoded data
    $clientId = $data->clientId;
    $newValues = $data->newValues;

    // Prepare and execute the update query
    $updateQuery = "UPDATE consommation SET ";
    $updates = [];
    foreach ($newValues as $field => $value) {
        $updates[] = "$field = '$value'";
    }
    $updateQuery .= implode(", ", $updates);
    $updateQuery .= " WHERE ID_Client= '$clientId'"; // Adjust the WHERE clause according to your table structure

    if (mysqli_query($connection, $updateQuery)) {
        echo "Records updated successfully";
    } else {
        echo "Error updating records: " . mysqli_error($connection);
    }
}
?>
