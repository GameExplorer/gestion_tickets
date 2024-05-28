<?php
include 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $departmentId = $_POST['departmentSelectUser'];

    if (strlen($password) > 32) {
        echo 'error: Password too long';
        exit;
    }

    $password = substr($password, 0, 32);

    $query = "INSERT INTO usuarios (nombre_usuario, pass_usuario, id_departamento) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $username, $password, $departmentId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>