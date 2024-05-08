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

<script>
    function toggleDateInputs(icon) {
        const dateInputsContainer = icon.nextElementSibling;
        if (dateInputsContainer) {
            dateInputsContainer.classList.toggle('show');
        }
    }

    function toggleDateInputsLastUpdated(icon) {
        const dateInputsContainer = icon.nextElementSibling;
        if (dateInputsContainer) {
            dateInputsContainer.classList.toggle('show');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const statusSelects = document.querySelectorAll('.status-select');
        statusSelects.forEach(select => {
            select.addEventListener('change', function () {
                console.log('status-select change event fired');
                const row = this.closest('tr');
                const incidentIdElement = row.querySelector('.incident-id');
                const statusCell = row.querySelector('.Status');
                const lastUpdatedCell = row.querySelector('.Last_Updated');

                // Send AJAX request to update status
                const xhr = new XMLHttpRequest();
                const incidentId = incidentIdElement.textContent.trim(); // Retrieve incident ID
                const status = this.value; // Retrieve selected status
                const params = `incident_id=${incidentId}&status=${encodeURIComponent(status)}`;
                lastUpdatedCell
                xhr.open('POST', 'includes/update_status.php', true);
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


        const lastUpdatedStartInput = document.getElementById('last-updated-start-date');
        const lastUpdatedEndInput = document.getElementById('last-updated-end-date');

        if (lastUpdatedStartInput && lastUpdatedEndInput) {
            lastUpdatedStartInput.addEventListener('change', applyLastUpdatedFilter);
            lastUpdatedEndInput.addEventListener('change', applyLastUpdatedFilter);
        }

        function applyLastUpdatedFilter() {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = tbody.getElementsByTagName('tr');

            const startDate = new Date(document.getElementById('last-updated-start-date').value);
            const endDate = new Date(document.getElementById('last-updated-end-date').value);

            endDate.setDate(endDate.getDate() + 1); // Include the selected end date

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const lastUpdatedCell = row.querySelector('td:nth-child(9)'); // Last Updated is the 9th column

                if (lastUpdatedCell) {
                    const lastUpdatedDate = new Date(lastUpdatedCell.textContent.trim());

                    const isVisible =
                        (!startDate || lastUpdatedDate >= startDate) &&
                        (!endDate || lastUpdatedDate <= endDate);

                    row.style.display = isVisible ? '' : 'none';
                }
            }
        }

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

        // Function filters data based on the input field values, also we can clear thse values
        function filterTable() {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0]; // body element
            const rows = tbody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let shouldDisplay = true;

                for (let j = 0; j < cells.length; j++) {
                    const targetCell = cells[j];
                    const filterInput = document.querySelector(`.filter-input[data-column="${j}"]`);
                    const filterSelect = document.querySelector(`.filter-select[data-column="${j}"]`);

                    if (filterInput) {
                        const inputValue = filterInput.value.trim().toUpperCase();
                        if (inputValue !== '' && inputValue !== 'Todo') {
                            const cellText = targetCell.textContent || targetCell.innerText;
                            if (cellText.toUpperCase().indexOf(inputValue) === -1) {
                                shouldDisplay = false;
                                break;
                            }
                        }
                    }

                    if (filterSelect) {
                        const selectValue = filterSelect.value.trim().toUpperCase();
                        if (selectValue !== '' && selectValue !== 'Todo') {
                            const cellText = targetCell.textContent || targetCell.innerText;
                            if (j === 7 && cellText.toUpperCase() !== selectValue) { // Prioridad column (index 7)
                                shouldDisplay = false;
                                break;
                            } else if (j !== 7 && cellText.toUpperCase().indexOf(selectValue) === -1) {
                                shouldDisplay = false;
                                break;
                            }
                        }
                    }
                }

                row.style.display = shouldDisplay ? '' : 'none';
            }
        }

        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function () {

                // Clear all input values
                filterInputs.forEach(input => {
                    input.value = '';
                });

                // Reset all select elements to their first option (All)
                filterSelects.forEach(select => {
                    select.selectedIndex = 0;
                });

                // Trigger filterTable function to reset table display
                filterTable('', '');
            });
        };
    });


</script>