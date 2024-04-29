<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Tickets table</title>
        <link rel="stylesheet" href="css/ticket_table_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/ticket_table_script.js"></script>
        <script>
            //script in another file if it doesn't work copy it here

        </script>
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

        // Default sorting parameters
        $order = isset($_GET['order']) ? $_GET['order'] : 'id_ticket';
        $sort = isset($_GET['sort']) && ($_GET['sort'] === 'DESC' || $_GET['sort'] === 'ASC') ? $_GET['sort'] : 'ASC';

        // Toggle sorting direction
        $newSort = ($sort === 'ASC') ? 'DESC' : 'ASC';

        $resultSet = $conn->query("SELECT * FROM tickets ORDER BY $order $sort");

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

        $result = $conn->query($sql);

        function getSortUrl($column)
        {
            global $order, $sort;
            $newSort = ($order === $column && $sort === 'ASC') ? 'DESC' : 'ASC';
            return "ticket_table.php?order=$column&sort=$newSort";
        }

        if ($result->num_rows > 0) {
            $sort == 'ASC' ? $sort = 'DESC' : $sort = 'ASC';
            echo "<table id='myTable'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=id_ticket&sort=$newSort'>ID Ticket</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=nombre&sort=$newSort'>Nombre</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=localizacion&sort=$newSort'>Localización</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=nombre_departamento&sort=$newSort'>Departamento</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=titulo&sort=$newSort'>Título</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=fecha_creacion&sort=$newSort'>Fecha Creación</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=estado&sort=$newSort'>Estado</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=prioridad&sort=$newSort'>Prioridad</a></th>";
            echo "<th><a class='sort-by' href='ticket_table.php?order=fecha_actualizacion&sort=$newSort'>Fecha Actualización</a></th>";
            echo "<th>Archivos</th>";
            echo "<th></th>";
            echo "</tr>";

            // Add dropdowns for filtering above each column header
            echo "<tr>";
            echo "<td><input type='text' class='filter-input' data-column='0'></td>"; //ID Column
            echo "<td><input type='text' class='filter-input' data-column='1'></td>"; //User column
        
            // Location filter column
            echo "<td><select class='filter-select' data-column='2'>
                <option value=''>Todo</option>
                <option value='Almacén Central'>Almacén Central</option>
                <option value='Las Palmas'>Las Palmas</option>
                <option value='S. Fernando'>S. Fernando</option>
                <option value='S/C Tenerife'>S/C Tenerife</option>
                <option value='Americas'>Americas</option>
                <option value='Pto. Rosario'>Pto. Rosario</option>
                <option value='Pto. Cruz'>Pto. Cruz</option>
                <option value='Lanzarote'>Lanzarote</option>
                <option value='Cristianos'>Cristianos</option>
                <option value='La Laguna'>La Laguna</option>
                <option value='Morro Jable'>Morro Jable</option>
                <option value='Galdar'>Galdar</option>
                <option value='M. Larache'>M. Larache</option>
                <option value='Vecindario'>Vecindario</option>
                <option value='LEOS'>LEOS</option>
                <option value='Travieso'>Travieso</option>
                <option value='Telde'>Telde</option>
                <option value='La Palma'>La Palma</option>
                <option value='Arguineguín'>Arguineguín</option>
                <option value='Jinamar'>Jinamar</option>
                </select></td>";

            // Department filter column
            echo "<td><select class='filter-select' data-column='3'>";
            echo "<option value=''>Todo</option>";

            $departmentQuery = "SELECT nombre_departamento FROM departamentos WHERE id_departamento != 0";
            $departmentResult = $conn->query($departmentQuery);

            if ($departmentResult->num_rows > 0) {
                while ($departmentRow = $departmentResult->fetch_assoc()) {
                    $departmentName = $departmentRow['nombre_departamento'];
                    echo "<option value='$departmentName'>$departmentName</option>";
                }
            }

            echo "</select></td>";
            echo "<td><input type='text' class='filter-input' data-column='4'></td>"; // Title column
        
            // Date filter column
            echo '<td>';
            echo '<div class="date-inputs-container">';
            echo '<i class="fas fa-calendar-alt date-filter-icon" onclick="toggleDateInputs(this)"></i>';
            echo '<div class="date-inputs">';
            echo '<label for="start-date">Desde:</label>';
            echo '<input type="date" id="start-date" class="filter-input" data-column="5"><br>';
            echo '<label for="end-date">Hasta:</label>';
            echo '<input type="date" id="end-date" class="filter-input" data-column="5">';
            echo '</div>';
            echo '</div>';
            echo '</td>';

            //Status column
            echo "<td><select class='filter-select' data-column='6'>
                  <option value=''>Todo</option>
                  <option value='Abierto'>Abierto</option>
                  <option value='En Progreso'>En Progreso</option>
                  <option value='En Espera'>En Espera</option>
                  <option value='Cerrado'>Cerrado</option>
              </select></td>";

            //Priority column
            echo "<td><select class='filter-select' data-column='7'> 
                  <option value=''>Todo</option>
                  <option value='Nuevo'>Nuevo</option>
                  <option value='Urgente'>Urgente</option>
                  <option value='Alta'>Alta</option>
                  <option value='Media'>Media</option>
                  <option value='Baja'>Baja</option>
              </select></td>";

            //Last Updated column
            echo "<td>";
            echo '<div class="date-inputs-container">';
            echo '<i class="fas fa-calendar-alt date-filter-icon" onclick="toggleDateInputsLastUpdated(this)"></i>';
            echo '<div class="date-inputs">';
            echo '<label for="last-updated-start-date">Desde:</label>';
            echo '<input type="date" id="last-updated-start-date" class="filter-input" data-column="8"><br>';
            echo '<label for="last-updated-end-date">Hasta:</label>';
            echo '<input type="date" id="last-updated-end-date" class="filter-input" data-column="8">';
            echo '</div>';
            echo '</div>';
            echo '</td>';

            echo "<td></td>"; // Empty cell for Files column
            echo '<td><button id="clearFiltersBtn">Borrar Filtros</button></td>';
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
                echo "<button class='tableButtons' onclick=\"window.location.href='view_ticket.php?ticket_id=" . $row["id_ticket"] . " '\">DETALLES</button>";
                //non logged -users shouldn't see this button and the status select button
                if (isset($_SESSION['loggedin'])) {
                    echo "<button class='tableButtons' onclick=\"removeRow(this)\">OCULTAR</button>";

                    echo "<select class='status-select'>";
                    echo "<option value='Abierto'" . ($row["estado"] === "Abierto" ? " selected" : "") . ">Abierto</option>";
                    echo "<option value='En Progreso'" . ($row["estado"] === "En Progreso" ? " selected" : "") . ">En Progreso</option>";
                    echo "<option value='En Espera'" . ($row["estado"] === "En Espera" ? " selected" : "") . ">En Espera</option>";
                    echo "<option value='Cerrado'" . ($row["estado"] === "Cerrado" ? " selected" : "") . ">Cerrado</option>";
                    echo "</select>";
                }

                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No tickets found.</td></tr>";
        }

        echo "</tbody>";
        echo "</table>";
        $conn->close();
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
                console.log('Last Updated Cell:', lastUpdatedCell);

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