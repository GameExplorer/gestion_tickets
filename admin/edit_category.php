<?php
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $catId = $_POST['catId'];
    $catName = $_POST['catName'];
    $catDeptId = $_POST['deptId'];

    $sql = "UPDATE categorias SET nombre_categoria = ?, id_departamento = ? WHERE id_categoria = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("sii", $catName, $catDeptId, $catId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();