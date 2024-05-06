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