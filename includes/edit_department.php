<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deptId = intval($_POST['deptId']);
    $deptName = $_POST['deptName'];

    if (!empty($deptName) && $deptId > 0) {
        $stmt = $conn->prepare("UPDATE departamentos SET nombre_departamento = ? WHERE id_departamento = ?");
        $stmt->bind_param('si', $deptName, $deptId);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Invalid input';
    }
} else {
    echo 'Invalid request method';
}

$conn->close();
?>