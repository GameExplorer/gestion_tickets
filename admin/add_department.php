<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deptName = $_POST['deptName'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // First query to insert into departamentos
        $stmt = $conn->prepare("INSERT INTO departamentos (nombre_departamento) VALUES (?)");
        $stmt->bind_param("s", $deptName);

        if (!$stmt->execute()) {
            throw new Exception('Error inserting into departamentos: ' . $stmt->error);
        }

        // Get the ID of the newly inserted department
        $newDeptId = $conn->insert_id;

        // Second query to insert into categorias
        $categoryName = 'Sin categoría';
        $stmt2 = $conn->prepare("INSERT INTO categorias (id_departamento, nombre_categoria) VALUES (?, ?)");
        $stmt2->bind_param("is", $newDeptId, $categoryName);

        if (!$stmt2->execute()) {
            throw new Exception('Error inserting into categorias: ' . $stmt2->error);
        }

        // Commit transaction
        $conn->commit();

        echo 'success';
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo 'error: ' . $e->getMessage();
    }

    $stmt->close();
    if (isset($stmt2)) {
        $stmt2->close();
    }
    $conn->close();
}