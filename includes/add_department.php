<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deptName = $_POST['deptName'];

    $stmt = $conn->prepare("INSERT INTO departamentos (nombre_departamento) VALUES (?)");
    $stmt->bind_param("s", $deptName);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>