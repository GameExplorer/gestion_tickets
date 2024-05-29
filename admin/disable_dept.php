<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deptId = intval($_POST['id']);
    $action = $_POST['action'];

    $disabled = ($action == 'disable') ? 1 : 0;
    $sql = "UPDATE departamentos SET disabled = ? WHERE id_departamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $disabled, $deptId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
