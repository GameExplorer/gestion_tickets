<?php


// Default sorting parameters
$order = isset($_GET['order']) ? $_GET['order'] : 'id_ticket';
$sort = isset($_GET['sort']) && ($_GET['sort'] === 'DESC' || $_GET['sort'] === 'ASC') ? $_GET['sort'] : 'ASC';

// Toggle sorting direction
$newSort = ($sort === 'ASC') ? 'DESC' : 'ASC';

$result = $conn->query($sql);

function getSortUrl($column)
{
    global $order, $sort;
    $newSort = ($order === $column && $sort === 'ASC') ? 'DESC' : 'ASC';
}

if ($result->num_rows > 0) {
    $sort == 'ASC' ? $sort = 'DESC' : $sort = 'ASC';
    echo "<div class='table-responsive'>";
    echo "<table id='myTable'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th class='sort-by'><a href='#' data-column='0'>ID Ticket <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='1'>Nombre <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='2'>Localización <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='3'>Departamento <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='4'>Título <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='5'>Fecha Creación <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='6'>Estado <i class='fas fa-sort'></i></a></th>";
    echo "<th><a>Usuario</a></th>";
    echo "<th><a>Dept</a></th>";
    echo "<th><a href='#' data-column='7'>Prioridad <i class='fas fa-sort'></i></a></th>";
    echo "<th><a href='#' data-column='8'>Fecha Actualización <i class='fas fa-sort'></i></a></th>";
    echo "<th>Archivos</th>";
    echo "<th></th>";
    echo "</tr>";

    // Add dropdowns for filtering above each column header
    echo "<tr>";
    echo "<td><input type='text' class='filter-input' data-column='0'></td>"; //ID Column
    echo "<td><input type='text' class='filter-input' data-column='1'></td>"; //User column

    // Location filter column
    if (isset($_SESSION['loggedin'])) {

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
    } else {
        echo "<td></td>";
    }

    // Department filter column
    if (isset($_SESSION['loggedin'])) {
        echo "<td></td>";

    } else {
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

    }
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
              </select></td>";

    // Ticket Closing Status
    echo "<td></td>";
    echo "<td></td>";

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

    echo "<td></td>";
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

        echo "<td>";
        if ($row["check_usuario"] == 0) {
            echo "<img src='assets/red-x-icon.svg' alt='file icon' class='statusIcons'>";

        } else {
            echo "<img src='assets/done-icon.svg' alt='file icon' class='statusIcons'>";
        }
        echo "</td>";
        echo "<td>";
        if ($row["check_dept"] == 0) {
            echo "<img src='assets/red-x-icon.svg' alt='file icon' class='statusIcons'>";
        } else {
            echo "<img src='assets/done-icon.svg' alt='file icon' class='statusIcons'>";
        }
        echo "</td>";

        echo "<td>" . $row["prioridad"] . "</td>";
        echo "<td class='Last_Updated'>" . $row["fecha_actualizacion"] . "</td>";

        echo "<td>";
        if ($row["FileCount"] > 0) {
            echo "<img src='assets/file_icon.svg' alt='file icon' class='icons'>";
        }
        echo "</td>";

        echo "<td class='button-group m-2'>";
        echo '<form action="view_ticket.php" method="post">' .
            '<input type="hidden" name="ticket_id" value="' . $row["id_ticket"] . '">' .
            '<button type="submit" class=\'tableButtons\'>DETALLES</button>' .
            '</form>';
        //non logged - users shouldn't see this button and the status select button
        if (isset($_SESSION['loggedin'])) {
            echo "<select class='form-select    status-select p-2'>";
            echo "<option value='Abierto'" . ($row["estado"] === "Abierto" ? " selected" : "") . ">Abierto</option>";
            echo "<option value='En Progreso'" . ($row["estado"] === "En Progreso" ? " selected" : "") . ">En Progreso</option>";
            echo "<option value='En Espera'" . ($row["estado"] === "En Espera" ? " selected" : "") . ">En Espera</option>";
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
echo "</div>";
$conn->close();
?>

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
        // Function to sort table rows
        function sortTable(columnIndex, order) {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            // Sort the rows based on the content of the specified column
            rows.sort((a, b) => {
                const aValue = a.getElementsByTagName('td')[columnIndex].textContent.trim();
                const bValue = b.getElementsByTagName('td')[columnIndex].textContent.trim();

                return isNaN(aValue) ? aValue.localeCompare(bValue) : aValue - bValue;
            });

            // Reverse the order if sorting is in DESC order (ASC order now)
            if (order === 'DESC') {
                rows.reverse();
            }

            // Re-append sorted rows to tbody
            rows.forEach(row => tbody.appendChild(row));
        }

        // Add click event listeners to table headers for sorting
        const headers = document.querySelectorAll('#myTable th');
        headers.forEach((header, index) => {
            header.addEventListener('click', function () {
                const columnIndex = index; // Index of the clicked column
                const order = this.dataset.sort === 'DESC' ? 'ASC' : 'DESC'; // Toggle sorting order
                sortTable(columnIndex, order);
                this.dataset.sort = order; // Update sorting order in dataset
            });
        });

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