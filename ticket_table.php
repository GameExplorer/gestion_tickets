<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>TABLA TICKETS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/ticket_table_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/ticket_table_script.js"></script>
    </head>

    <body>
        <?php
        $pageTitle = "Lista Tickets";
        include 'includes/menu.php';
        include 'includes/connection.php';
        // Retrieve user's location using functions from numTienda.php
        require_once 'includes/numTienda.php';
        require_once 'includes/timezone_setting.php';
        $nombre = $sede[$n];

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        } else {
            $order = 'titulo';
        }

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'ASC';
        }

        // Check if user is logged in
        if (isset($_SESSION['loggedin'])) {
            // User is logged in, retrieve department ID
            $departmentID = $_SESSION['department_id'];
            if ($departmentID == 0) {
                $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
                FROM tickets
                INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
                LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
                WHERE tickets.oculto = 0
                GROUP BY tickets.id_ticket
                ORDER BY tickets.titulo ASC";

            } else {
                $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE tickets.id_departamento = $departmentID AND tickets.oculto = 0
            GROUP BY tickets.id_ticket
            ORDER BY $order $sort";
            }
        } else {
            // User is not logged in, retrieve tickets by location only
            $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE tickets.localizacion = '$nombre' AND tickets.oculto = 0
            GROUP BY tickets.id_ticket
            ORDER BY $order $sort";
        }

        include 'includes/tickets.php';

        ?>
    </body>

</html>
