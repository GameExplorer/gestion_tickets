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
        <style>
            .green-text {
                color: green;
            }

            .default-text {
                color: inherit;
            }

            body {
                margin: 0 auto;
                width: 100vw;
                text-align: center;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
            }

            h1 {
                text-align: center;
                text-transform: uppercase;
                font-weight: 900;
                padding-top: 15px;
                padding-bottom: 10px;
            }

            /* Table style */
            table {
                margin: 0 auto;
                width: 95%;
                text-align: center;
                border-collapse: collapse;
            }

            thead {
                display: table-header-group;
            }

            th,
            td {
                text-align: center;
            }


            th {
                padding: 5px 15px 15px 5px;
                width: 600px;
                height: 50px;
                font-size: 0.9em;
                display: table-cell;
                text-transform: uppercase;
                font-weight: 800;
                background-color: #96c565;
                border: 21px gray solid;
                cursor: pointer;
            }

            th:nth-child(12) {
                width: 120px;
            }

            th:last-child {
                width: 150px;
            }

            th:nth-child(even) {
                background-color: #5fa142;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            th a,
            td a {
                text-decoration: none;
                color: white;
                display: block;
                width: 100%;

            }

            th a.sort-by {
                padding-right: 18px;
                position: relative;
            }

            a.sort-by:before,
            a.sort-by:after {
                border: 4px solid transparent;
                content: "";
                display: block;
                height: 0;
                right: 5px;
                top: 50%;
                position: absolute;
                width: 0;
            }

            a.sort-by:before {
                border-bottom-color: black;
                margin-top: -9px;
            }

            a.sort-by:after {
                border-top-color: black;
                margin-top: 1px;
            }

            th:focus,
            th:hover {
                background-color: #eea236;
            }

            .button-group {
                display: flex;
                justify-content: space-between;
            }

            button {
                background-color: #4caf50;
                font-size: 1em;
                color: white;
                padding: 10px 10px;
                border: none;
                cursor: pointer;
            }

            #clearFiltersBtn {
                width: 120px;
                background-color: #f44336;
                font-size: 1em;
                color: white;
                margin: 5px;
                margin-right: 250px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .Status {
                font-weight: bold;
                text-transform: uppercase;
            }

            /* Table buttons */
            .tableButtons {
                width: 120px;
                height: 50px;
                background-color: #4caf50;
                font-size: 1.1em;
                color: white;
                margin: 0 10px 5px 0;
                border: none;
                cursor: pointer;
                border-radius: 5px;
            }

            .tableButtons:hover {
                background-color: white;
                color: #4caf50;
                font-weight: 600;
                border: 2px solid #4caf50;
                transition: 0.25s ease-in-out;
            }

            /*File Icons */
            .icons {
                justify-content: center;
                object-fit: contain;
                width: 50px;
                height: 50px;
            }

            .statusIcons {
                width: 30px;
                height: 30px;
                object-fit: contain;
            }

            /* Filter inputs*/
            .filter-input {
                width: 100%;
                padding: 3px;
                box-sizing: border-box;
                margin-top: 10px;
            }

            .filter-select {
                width: 100%;
                padding: 5px 15px 5px 10px;
                box-sizing: border-box;
                margin-top: 10px;
            }

            /* Date Filter */
            .date-inputs-container {
                position: relative;
                justify-content: center;
                align-items: center;
            }

            .date-filter-icon {
                cursor: pointer;
            }

            .date-inputs {
                position: fixed;
                background-color: #fff;
                border: 1px solid #ccc;
                padding: 15px;
                box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
                display: none;
            }

            .date-inputs.show {
                display: block;
                z-index: 1000;
            }

            /* Tooltip */
            .hover-me:hover+.tooltip {
                opacity: 1;
            }

            .tooltip {
                position: absolute;
                transform-origin: top center;
                min-width: 100px;
                padding: 0.5rem;
                border-radius: 0.4rem;
                background: black;
                color: white;
                opacity: 0;
                transition: opacity 0.2s ease;
            }

            .tooltip::after {
                content: "";
                position: absolute;
                height: 0;
                width: 0;
                top: 0%;
                left: 50%;
                border-style: solid;
                border-color: black transparent transparent transparent;
                transform: translateX(-50%);
                -moz-transform: translateX(-50%);
                -webkit-transform: translateX(-50%);
            }

            .rowborder {
                border-top: 1px solid gray;
            }

            .date-inputs {
                display: none;
            }

            .date-inputs.show {
                display: block;
            }
        </style>
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