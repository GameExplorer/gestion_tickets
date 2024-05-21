<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';
include 'numTienda.php';
include 'upload_config.php';
require_once 'timezone_setting.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data
    $department = $_POST['department'];
    $title = $_POST['title'];
    $name = $_POST['sender'];
    $location = $nombre;
    $description = $_POST['description'];
    $category = $_POST['category'];
    $ticketOpen = date("Y-m-d H:i:s");
    $lastUpdated = date("Y-m-d H:i:s");
    $errorFound = false;

    // Insert ticket details into database
    $sql = "INSERT INTO tickets (id_departamento, titulo, nombre, localizacion, prioridad, descripcion, categoria, estado, check_usuario, check_dept, fecha_creacion, fecha_actualizacion, oculto, leido_localizacion, leido_departamento) VALUES ('$department','$title', '$name', '$location', 'Nuevo', '$description', '$category', 'Abierto', '0', '0','$ticketOpen', '$lastUpdated', '0', '0', '0')";
    if ($conn->query($sql) === TRUE) {
        $ticketId = $conn->insert_id;

        foreach ($_FILES['attachment']['name'] as $key => $fileName) {
            if ($_FILES['attachment']['size'][$key] > 0) {

                // Check file type
                $fileType = pathinfo($_FILES['attachment']['name'][$key], PATHINFO_EXTENSION);
                if (!in_array(strtolower($fileType), $fileTypeRestrictions)) {
                    echo "<script>alert('" . $errorMessages['invalidFileType'] . "');</script>";
                    $errorFound = true;
                    break;
                }

                // Rename file
                $newFileName = "ticket" . $ticketId . "_archivo" . ($key + 1) . "." . strtolower($fileType);
                $targetFilePath = $targetDir . $newFileName;

                // Check file size
                if ($_FILES['attachment']['size'][$key] > $maxFileSizeMB * 1024 * 1024) {
                    echo "<script>alert('" . $errorMessages['fileSizeExceedLimit'] . "');</script>";
                    $errorFound = true;
                    break;
                }

                // Upload file
                if (move_uploaded_file($_FILES['attachment']['tmp_name'][$key], $targetFilePath)) {
                    // Insert attachment details into database
                    $sql = "INSERT INTO archivos (id_ticket, nombre_archivo) VALUES ('$ticketId', '$newFileName')";
                    $conn->query($sql);
                } else {
                    echo "<script>alert('Error al subir archivo.');</script>";
                    $errorFound = true;
                    break;
                }
            }
        }
        if (!$errorFound) {
            //echo "<script>alert('Ticket enviado.');</script>";
            echo "<script>window.location.href = '../index.php';</script>";

        } else {
            // Delete the ticket if there's any error with files
            $sql = "DELETE FROM tickets WHERE id_ticket = $ticketId";
            $conn->query($sql);
            echo "<script>alert('Error al enviar ticket.');</script>";
            echo "<script>window.location.href = '../ticket_form.php?";
            echo "title=" . urlencode($title) . "&sender=" . urlencode($name) . "&description=" . urlencode($description) . "&category=" . urlencode($category);
            echo "';</script>";
        }
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

$conn->close();