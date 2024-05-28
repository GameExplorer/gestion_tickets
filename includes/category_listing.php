<?php
include 'connection.php';

if (isset($_GET['department'])) {
    $department = $_GET['department'];

    $sql = "SELECT nombre_categoria FROM categorias WHERE id_departamento = ? AND disabled = 0";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $department);
    $stmt->execute();

    $result = $stmt->get_result();

    $categories = array();

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['nombre_categoria'];
    }

    $stmt->close();

    echo json_encode($categories);
} else {
    echo json_encode(array()); // Return an empty array
}