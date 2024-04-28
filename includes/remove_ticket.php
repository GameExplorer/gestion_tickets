<?php
session_start();
include 'connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $incidentId = $_POST['incident_id'];

    // Prepare and execute SQL statement to update 'hidden' status
    $sql = "UPDATE tickets SET oculto = 1 WHERE id_ticket = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $incidentId);
    $stmt->execute();

    // Check for successful update
    if ($stmt->affected_rows === 1) {
        echo "Exito"; // Send success message to Ajax request
    } else {
        echo "Error: " . $conn->error; // Send error message
    }

    $stmt->close();
}

$conn->close();
?>