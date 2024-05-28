<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $response = '';

    if (isset($_POST['userName']) && isset($_POST['userDeptId'])) {
        $userName = $_POST['userName'];
        $userDeptId = $_POST['userDeptId'];

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

?>
