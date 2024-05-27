<?php

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['id']);
    $action = $_POST['action'];

    $disabled = ($action == 'disable') ? 1 : 0;
    $sql = "UPDATE usuarios SET disabled = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $disabled, $userId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
?>