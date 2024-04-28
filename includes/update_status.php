<?php
include 'connection.php';

$incident_id = $_POST['incident_id'];
$status = $_POST['status'];

$sql = "UPDATE tickets SET estado = '$status', fecha_actualizacion = NOW() WHERE id_ticket = $incident_id";
$result = $conn->query($sql);


if ($result) {
    // Retrieve and return the current timestamp
    $sql = "SELECT NOW() AS updated_at";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $updated_timestamp = $row['updated_at'];
    echo $updated_timestamp;
} else {
    echo "Error al actualizar estado";
}

$conn->close();
