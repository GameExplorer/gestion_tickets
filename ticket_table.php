<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">

        <title>Tickets en curso</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/ticket_table_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <?php

        include 'includes/connection.php';

        $pageTitle = "TICKETS EN CURSO";

        include 'includes/menu.php';
        // Retrieve user's location using functions from numTienda.php
        require_once 'includes/numTienda.php';
        require_once 'includes/timezone_setting.php';
        $nombre = $sede[$n];


        // Default sorting parameters
        $order = isset($_GET['order']) ? $_GET['order'] : 'titulo';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';

        // Allowed columns 
        $allowed_columns = ['id_ticket', 'nombre', 'localizacion', 'nombre_departamento', 'titulo', 'fecha_creacion', 'estado', 'prioridad', 'fecha_actualizacion'];
        if (!in_array($order, $allowed_columns)) {
            $order = 'titulo';
        }

        // Validate sort direction
        if ($sort !== 'DESC' && $sort !== 'ASC') {
            $sort = 'ASC';
        }

        $sql_base = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.check_usuario, tickets.check_dept, tickets.leido_localizacion, tickets.leido_departamento, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
                FROM tickets
                INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
                LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
                WHERE tickets.oculto = 0";

        // Check if user is logged in
        if (isset($_SESSION['loggedin'])) {
            // User is logged in, retrieve department ID
            $departmentID = $_SESSION['department_id'];
            if ($departmentID == 0) {
                $sql = "$$sql_base GROUP BY tickets.id_ticket ORDER BY tickets.titulo ASC";

            } else {
                $sql = "$sql_base AND tickets.id_departamento = $departmentID
            GROUP BY tickets.id_ticket
            ORDER BY $order $sort";
            }
        } else {
            // User is not logged in, retrieve tickets by location only
            $sql = "$sql_base
            WHERE tickets.localizacion = '$nombre' 
            GROUP BY tickets.id_ticket
            ORDER BY $order $sort";
        }

        include 'includes/tickets.php';

        ?>
    </body>

</html>