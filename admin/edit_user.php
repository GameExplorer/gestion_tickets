<?php
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $response = '';

    // Check current department of the user
    $sql = "SELECT id_departamento FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($currentDept);
    $stmt->fetch();
    $stmt->close();

    if (isset($_POST['userName']) && isset($_POST['userDeptId'])) {
        $userName = $_POST['userName'];
        $userDeptId = $_POST['userDeptId'];

        if ($currentDept == 0 && $userDeptId != $currentDept) {
            $response .= "Users with department 0 are not allowed to change departments. ";
        } else {
            $sql = "UPDATE usuarios SET nombre_usuario = ?, id_departamento = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $userName, $userDeptId, $userId);

            if ($stmt->execute()) {
                $response .= 'User details updated successfully. ';
            } else {
                $response .= "Error updating user details: " . $stmt->error . ". ";
            }

            $stmt->close();
        }
    }

    if (isset($_POST['newPassword'])) {
        $newPassword = $_POST['newPassword'];

        $sql = "UPDATE usuarios SET pass_usuario = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newPassword, $userId);

        if ($stmt->execute()) {
            $response .= 'Password updated successfully.';
        } else {
            $response .= "Error updating password: " . $stmt->error;
        }

        $stmt->close();
    }

    echo $response;
}