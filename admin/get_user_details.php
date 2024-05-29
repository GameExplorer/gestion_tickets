<?php
include '../includes/connection.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $query = "SELECT id_usuario, nombre_usuario, pass_usuario,   id_departamento FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    echo json_encode($user);
}
