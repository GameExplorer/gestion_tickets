<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Tabla Tickets";

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Tickets Table</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                margin: 0 auto;
                width: 100vw;
                text-align: center;
                font-family: Verdana, Geneva, Tahoma, sans-serif
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
                overflow: hidden;
            }

            thead {
                display: table-header-group;
            }

            td {}

            th,
            td {
                text-align: center;
            }

            th {
                padding: 5px 10px 5px 10px;
                width: 125px;
                font-size: 1.0em;
                display: table-cell;
                text-transform: uppercase;
                font-weight: 900;
                background-color: #96c565;
                color: white;
            }

            th:nth-child(10) {
                width: 100px;
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
            }

            button {
                background-color: #4CAF50;
                font-size: 1em;
                color: white;
                padding: 10px 10px;
                border: none;
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
                background-color: #4CAF50;
                font-size: 1.1em;
                color: white;
                margin: 10px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
            }

            .tableButtons:hover {
                background-color: white;
                color: #4CAF50;
                font-weight: 600;
                border: 2px solid #4CAF50;
                transition: 0.25s ease-in-out;
            }

            .hidden {
                display: none;
            }

            /*File Icons */
            .icons {
                justify-content: center;
                object-fit: contain;
                width: 50px;
                height: 50px;
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
        </style>
    </head>

    <body>
        <?php

        // Include necessary files
        include 'includes/connection.php';
        include 'includes/menu.php';

        // Retrieve user's location using functions from numTienda.php
        require_once 'includes/numTienda.php';
        $nombre = $sede[$n];

        // Check if user is logged in
        if (isset($_SESSION['loggedin'])) {
            // User is logged in, retrieve department ID
            $departmentID = $_SESSION['department_id'];
            if ($departmentID == 0){
                $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
                FROM tickets
                INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
                LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
                WHERE tickets.oculto = 0 AND tickets.estado = 'Abierto'
                GROUP BY tickets.id_ticket
                ORDER BY tickets.titulo ASC";
            } else {
            $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE tickets.id_departamento = $departmentID AND tickets.oculto = 0 AND tickets.estado = 'Abierto'
            GROUP BY tickets.id_ticket
            ORDER BY tickets.titulo ASC";
            }
        } else {
            // User is not logged in, retrieve tickets by location only
            $sql = "SELECT tickets.id_ticket, tickets.nombre, tickets.localizacion, tickets.prioridad, departamentos.nombre_departamento, tickets.titulo, tickets.fecha_creacion, tickets.estado, tickets.fecha_actualizacion, COUNT(archivos.id_archivo) AS FileCount
            FROM tickets
            INNER JOIN departamentos ON tickets.id_departamento = departamentos.id_departamento
            LEFT JOIN archivos ON tickets.id_ticket = archivos.id_ticket
            WHERE tickets.localizacion = '$nombre' AND tickets.oculto = 0 AND tickets.estado = 'Abierto'
            GROUP BY tickets.id_ticket
            ORDER BY tickets.titulo ASC";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table id='myTable'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID Ticket</th>";
            echo "<th>Nombre</th>";
            echo "<th>Localización</th>";
            echo "<th>Departamento</th>";
            echo "<th>Título</th>";
            echo "<th>Fecha Creación</th>";
            echo "<th>Estado</th>";
            echo "<th>Prioridad</th>";
            echo "<th>Fecha Actualización</th>";
            echo "<th>Archivos</th>";
            echo "<th></th>"; // Actions column
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='incident-id'>" . $row["id_ticket"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . $row["localizacion"] . "</td>";
                echo "<td>" . $row["nombre_departamento"] . "</td>";
                echo "<td>" . $row["titulo"] . "</td>";
                echo "<td>" . $row["fecha_creacion"] . "</td>";
                echo "<td class='Status'>" . $row["estado"] . "</td>";
                echo "<td>" . $row["prioridad"] . "</td>";
                echo "<td class='Last_Updated'>" . $row["fecha_actualizacion"] . "</td>";

                echo "<td>";
                if ($row["FileCount"] > 0) {
                    echo "<img src='assets/file_icon.svg' alt='file icon' class='icons'>";
                } else {
                    echo "<img src='assets/no_file_icon.svg' alt='no file icon' class='icons'>";
                }
                echo "</td>";

                echo "<td class='button-group'>";
                echo '<form action="view_ticket.php" method="post">' .
                     '<input type="hidden" name="ticket_id" value="' . $row["id_ticket"] . '">' .
                     '<button type="submit" class=\'tableButtons\'>DETALLES</button>' .
                     '</form>';
                echo "<button class='tableButtons' onclick=\"removeRow(this)\">OCULTAR</button>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay tickets abiertos.</p>";
        }

        $conn->close();
        ?>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const statusSelects = document.querySelectorAll('.status-select');
                statusSelects.forEach(select => {
                    select.addEventListener('change', function () {
                        const row = this.closest('tr');
                        const incidentIdElement = row.querySelector('.incident-id');
                        const statusCell = row.querySelector('.Status');
                        const lastUpdatedCell = row.querySelector('.Last_Updated');

                        // Send AJAX request to update status
                        const xhr = new XMLHttpRequest();
                        const incidentId = incidentIdElement.textContent.trim(); // Retrieve incident ID
                        const status = this.value; // Retrieve selected status
                        const params = `incident_id=${incidentId}&status=${encodeURIComponent(status)}`;

                        xhr.open('POST', 'update_status.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                // Update status and time in cell using response
                                const updatedTimestamp = xhr.responseText;
                                console.log('Updated Timestamp:', updatedTimestamp);

                                if (lastUpdatedCell) {
                                    lastUpdatedCell.textContent = updatedTimestamp;
                                } else {
                                    console.error('Last Updated Cell not found.');
                                }

                                if (statusCell) {
                                    statusCell.textContent = status;
                                } else {
                                    console.error('Status Cell not found.');
                                }

                                alert('Status updated successfully');
                            } else {
                                alert('Error updating status: ' + xhr.responseText);
                            }
                        };

                        xhr.send(params);
                    });
                });

                const filterInputs = document.querySelectorAll('.filter-input');
                const filterSelects = document.querySelectorAll('.filter-select');

                filterInputs.forEach(input => {
                    input.addEventListener('keyup', function () {
                        const columnIndex = this.dataset.column;
                        filterTable(columnIndex, this.value.trim());
                    });
                });

                filterSelects.forEach(select => {
                    select.addEventListener('change', function () {
                        const columnIndex = this.dataset.column;
                        filterTable(columnIndex, this.value);
                    });
                });

                function filterTable(columnIndex, filterValue) {
                    const table = document.getElementById('myTable');
                    const tbody = table.getElementsByTagName('tbody')[0]; // Get the tbody element
                    const rows = tbody.getElementsByTagName('tr');

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = row.getElementsByTagName('td');
                        const targetCell = cells[columnIndex];

                        if (targetCell) {
                            const cellText = targetCell.textContent || targetCell.innerText;

                            if (filterValue === '' || cellText.toUpperCase().indexOf(filterValue.toUpperCase()) > -1) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    }
                }
            });
        </script>
    </body>

</html>