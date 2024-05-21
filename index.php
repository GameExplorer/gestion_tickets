<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Tickets Abiertos</title>
        <link rel="stylesheet" href="css/ticket_table_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <?php
        include 'includes/connection.php';
        $pageTitle = "Tickets Abiertos";
        include 'includes/menu.php';
        // Retrieve user's location using functions from numTienda.php
        require_once 'includes/numTienda.php';
        require_once 'includes/timezone_setting.php';
        $statusFilter = 'Abierto';

        $_REQUEST['estado'] = 'Abierto';

        if ($statusFilter) {

            if (isset($_SESSION['loggedin'])) {
                // User is logged in, retrieve department ID
                $departmentID = $_SESSION['department_id'];
                if ($departmentID == 0) {
                    $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.check_usuario, tickets.check_dept, tickets.leido_departamento, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
                FROM tickets
                INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
                LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
                WHERE tickets.oculto = 0 AND estado = '$statusFilter'
                GROUP BY tickets.id_ticket
                ORDER BY tickets.titulo ASC";

                } else {
                    $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.check_usuario, tickets.check_dept, tickets.leido_localizacion, tickets.leido_departamento, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE tickets.id_departamento = $departmentID AND tickets.oculto = 0 AND estado = '$statusFilter'
            GROUP BY tickets.id_ticket";
                }
            } else {
                // User is not logged in, retrieve tickets by location only
                $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.check_usuario, tickets.check_dept, tickets.leido_localizacion, tickets.leido_departamento, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE tickets.localizacion = '$nombre' AND tickets.oculto = 0 AND estado = '$statusFilter'
            GROUP BY tickets.id_ticket";
            }
        }

        include 'includes/tickets.php';

        ?>

    </body>

</html>