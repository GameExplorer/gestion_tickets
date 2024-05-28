<?php
include 'connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryName = $_POST['categoryName'];
    $departmentId = $_POST['departmentSelect']; 

    $query = "INSERT INTO categorias (id_departamento, nombre_categoria) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $departmentId, $categoryName);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>