<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Tabla Tickets";
include 'includes/menu.php';
include 'includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Tickets Table</title>
        <link rel="stylesheet" href="css/ticket_table_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <?php

        // Include necessary files
        include 'includes/connection.php';
        $statusFilter = 'Abierto';

        $_REQUEST['estado'] = 'Abierto';

        if ($statusFilter) {
            $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE estado = '$statusFilter'
            GROUP BY tickets.id_ticket";
        }

        include 'includes/tickets.php';

        ?>

    </body>

</html>