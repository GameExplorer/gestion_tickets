<?php
include 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $sql = "SELECT id_departamento, nombre_departamento FROM departamentos WHERE id_departamento != 0 AND disabled = 0";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Database Query Error: " . $conn->error);
    } else {
        $departments = array();
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        echo json_encode($departments);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>