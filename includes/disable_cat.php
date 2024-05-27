<?php

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catId = intval($_POST['id']);
    $action = $_POST['action'];

    $disabled = ($action == 'disable') ? 1 : 0;
    $sql = "UPDATE categorias SET disabled = ? WHERE id_categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $disabled, $catId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
?>