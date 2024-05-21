<?php
include 'connection.php';
require_once 'timezone_setting.php';

$incident_id = $_POST['incident_id'];
$status = $_POST['status'];

$sql = "UPDATE tickets SET estado = '$status', fecha_actualizacion = NOW(), check_usuario = '0', check_dept = '0', oculto = '0', leido_localizacion = '0' WHERE id_ticket = $incident_id";
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