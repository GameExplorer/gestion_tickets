<?php

// Default sorting parameters
$order = isset($_GET['order']) ? $_GET['order'] : 'id_ticket';
$sort = isset($_GET['sort']) && ($_GET['sort'] === 'DESC' || $_GET['sort'] === 'ASC') ? $_GET['sort'] : 'ASC';

// Whitelist allowed columns to sort by
$allowed_columns = ['id_ticket', 'nombre', 'localizacion', 'nombre_departamento', 'titulo', 'fecha_creacion', 'estado', 'prioridad', 'fecha_actualizacion'];
if (!in_array($order, $allowed_columns)) {
    $order = 'id_ticket';
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sort == 'ASC' ? $sort = 'DESC' : $sort = 'ASC';
    echo "<div class='table-responsive'>";
    echo "<table id='myTable'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th class='sort-by'>
            <a href='#' data-column='0' class='hover-me'>ID Ticket <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por ID Ticket</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='1' class='hover-me'>Nombre <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Nombre</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='2' class='hover-me'>Localización <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Localización</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='3' class='hover-me'>Departamento <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Departamento</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='4' class='hover-me'>Título <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Título</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='5' class='hover-me'>Fecha Creación <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Fecha Creación</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='6' class='hover-me'>Estado <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Estado</div>
          </th>";
    echo "<th class=''>
            <a href='#' data-column='7'>Cerrado Usuario <i class='fas fa-sort'></i></a>
          </th>";
    echo "<th class=''>
            <a href='#' data-column='8'>Cerrado Departamento <i class='fas fa-sort'></i></a>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='9' class='hover-me'>Prioridad <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Prioridad</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='10' class='hover-me'>Fecha Actualización <i class='fas fa-sort'></i></a>
            <div class='tooltip'>Ordenar por Fecha Actualización</div>
          </th>";
    echo "<th class='sort-by'>
            <a href='#' data-column='11'>Archivos <i class='fas fa-sort'></i></a>
          </th>";
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
    echo "<td><select class='filter-select' data-column='9'> 
                  <option value=''>Todo</option>
                  <option value='Nuevo'>Nuevo</option>
                  <option value='Urgente'>Urgente</option>
                  <option value='Alta'>Alta</option>
                  <option value='Media'>Media</option>
                  <option value='Baja'>Baja</option>
              </select></td>";

    //Last Updated column
    echo "<td>";
    echo "</td>";

    echo "<td></td>";
    echo '<td><button id="clearFiltersBtn">Borrar Filtros</button></td>';
    echo "</tr>";

    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        if (isset($_SESSION['loggedin']) && !$row["leido_departamento"]) {
            // changes color of the row if there is a change for dept
            echo "<tr style='background-color:#d9f3ff;' class='rowborder'>";

        } else if (!isset($_SESSION['loggedin']) && !$row["leido_localizacion"]) {
            // changes color of the row if there is a change for non logged user
            echo "<tr style='background-color:#d9f3ff;' class='rowborder'>";

        } else {
            // no changes it stays black
            echo "<tr class='rowborder'>";

        }

            //The rest of the table
            echo "<td class='incident-id' data-column='0'>" . $row["id_ticket"] . "</td>";
            echo "<td data-column='1'>" . $row["nombre"] . "</td>";
            echo "<td data-column='2'>" . $row["localizacion"] . "</td>";
            echo "<td data-column='3'>" . $row["nombre_departamento"] . "</td>";
            echo "<td data-column='4'>" . $row["titulo"] . "</td>";
            echo "<td data-column='5'>" . $row["fecha_creacion"] . "</td>";
            echo "<td class='Status' data-column='6'>" . $row["estado"] . "</td>";

        echo "<td data-column='7'>";
        if ($row["check_usuario"] == 0) {
            echo "<img src='assets/red-x-icon.svg' alt='file icon' class='hover-me statusIcons'>
            <div class='tooltip'>Usuario marcado como sin resolver</div>";

        } else {
            echo "<img src='assets/done-icon.svg' alt='file icon' class='hover-me statusIcons'>
            <div class='tooltip'>Usuario marcado como solucionado</div>";
        }
        echo "</td>";
        echo "<td data-column='8'>";
        if ($row["check_dept"] == 0) {
            echo "<img src='assets/red-x-icon.svg' alt='file icon' class='hover-me statusIcons'>
            <div class='tooltip'>Departamento marcado como sin resolver</div>";
        } else {
            echo "<img src='assets/done-icon.svg' alt='file icon' class='hover-me statusIcons'>
            <div class='tooltip'>Departamento marcado como resuelto</div>";
        }
        echo "</td>";

        echo "<td data-column='9'>" . $row["prioridad"] . "</td>";
        echo "<td class='Last_Updated' data-column='10'>" . $row["fecha_actualizacion"] . "</td>";

        echo "<td data-column='11'>";
        if ($row["FileCount"] > 0) {
            echo "<img src='assets/file_icon.svg' alt='file icon' class='icons hover-me'>
            <div class='tooltip'>El ticket contiene archivos</div>";
        }
        echo "</td>";
        echo "<td class='button-group m-2'>";
        echo '<form action="view_ticket.php" method="post">' .
            '<input type="hidden" name="ticket_id" value="' . $row["id_ticket"] . '">' .
            '<button type="submit" class=\'tableButtons\'>DETALLES</button>' .
            '</form>';

        //non logged - users can't see the status select button
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
    echo "<tr><td colspan='11'>No se han encontrado tickets.</td></tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "<div class='pagination d-flex justify-content-end align-items-center px-5 py-2'>";
echo "<button id='prevPageBtn' class='btn btn-primary me-3'><i class='fa-solid fa-arrow-left'></i></button>";
echo "<span class='pt-0 px-2'>Página <span id='currentPage'></span> de <span id='totalPages'></span></span>";
echo "<button id='nextPageBtn' class='btn btn-primary ms-3'><i class='fa-solid fa-arrow-right'></i></button>";
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
                        const updatedTimestamp = xhr.responseText;

                        if (lastUpdatedCell) {
                            lastUpdatedCell.textContent = updatedTimestamp;
                        }

                        if (statusCell) {
                            statusCell.textContent = status;
                        }

                    } else {
                        alert('Error al actualizar estado: ' + xhr.responseText);
                    }
                };
                xhr.send(params);
            });
        });

        const rowsPerPage = 10; // Number of rows to display per page
        let currentPage = 1; // Current page number
        let totalPages; // Total number of pages
        let visibleRows = []; // Array to store rows that pass the filter

        function displayTable() {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            // Hide all rows initially
            rows.forEach(row => (row.style.display = 'none'));

            // Determine the range of rows to display based on the current page
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const rowsToDisplay = visibleRows.slice(start, end);

            // Display the rows for the current page
            rowsToDisplay.forEach(row => (row.style.display = ''));

            // Update pagination info
            totalPages = Math.ceil(visibleRows.length / rowsPerPage);
            document.getElementById('currentPage').textContent = currentPage;
            document.getElementById('totalPages').textContent = totalPages;
        }

        // Function to sort table rows
        function sortTable(columnIndex, order) {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            // Sort the rows based on the content of the specified column
            rows.sort((a, b) => {
                const aValue = getCellValue(a, columnIndex);
                const bValue = getCellValue(b, columnIndex);

                if (!isNaN(aValue) && !isNaN(bValue)) {
                    return order === 'ASC' ? aValue - bValue : bValue - aValue;
                } else {
                    return order === 'ASC' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                }
            });

            // Re-append sorted rows to tbody
            rows.forEach(row => tbody.appendChild(row));

            // Update the visibleRows array and display the first page
            filterTable();
        }

        // Helper function to get cell value based on column index
        function getCellValue(row, index) {
            const cell = row.getElementsByTagName('td')[index];
            if (index === 7 || index === 8) { // Assuming column indices 7 and 8 correspond to "Usuario" and "Dept" respectively
                const imageUrl = cell.querySelector('img').getAttribute('src');
                return imageUrl === 'assets/done-icon.svg' ? true : false;
            } else if (index === 11) {
                const imageUrl = cell.querySelector('img').getAttribute('src');
                return imageUrl === 'assets/file_icon.svg' ? true : false;
            } else {
                return cell.textContent.trim();
            }
        }

        // Add click event listeners to table headers for sorting
        const headers = document.querySelectorAll('#myTable th.sort-by a');
        headers.forEach(header => {
            header.addEventListener('click', function () {
                const columnIndex = parseInt(this.dataset.column);
                const order = this.dataset.sort === 'DESC' ? 'ASC' : 'DESC';
                sortTable(columnIndex, order);
                this.dataset.sort = order;
            });
        });

        // Pagination functions
        document.getElementById('prevPageBtn').addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                displayTable();
            }
        });

        document.getElementById('nextPageBtn').addEventListener('click', function () {
            if (currentPage < totalPages) {
                currentPage++;
                displayTable();
            }
        });

        // Filter functions
        const filterInputs = document.querySelectorAll('.filter-input');
        const filterSelects = document.querySelectorAll('.filter-select');

        filterInputs.forEach(input => {
            input.addEventListener('keyup', function () {
                currentPage = 1; // Reset to first page when filter is applied
                filterTable();
            });
        });

        filterSelects.forEach(select => {
            select.addEventListener('change', function () {
                currentPage = 1; // Reset to first page when filter is applied
                filterTable();
            });
        });

        function filterTable() {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            // Clear the visibleRows array
            visibleRows = [];

            rows.forEach(row => {
                let shouldDisplay = true;
                const cells = row.getElementsByTagName('td');

                filterInputs.forEach(input => {
                    const inputValue = input.value.trim().toUpperCase();
                    const cellValue = cells[input.dataset.column].textContent.trim().toUpperCase();
                    if (inputValue && !cellValue.includes(inputValue)) {
                        shouldDisplay = false;
                    }
                });

                filterSelects.forEach(select => {
                    const selectValue = select.value.trim().toUpperCase();
                    const cellValue = cells[select.dataset.column].textContent.trim().toUpperCase();
                    if (selectValue && selectValue !== 'TODO' && cellValue !== selectValue) {
                        shouldDisplay = false;
                    }
                });

                if (shouldDisplay) {
                    visibleRows.push(row);
                }
            });

            // Display the first page of the filtered results
            displayTable();
        }

        document.getElementById('clearFiltersBtn').addEventListener('click', function () {
            filterInputs.forEach(input => (input.value = ''));
            filterSelects.forEach(select => (select.selectedIndex = 0));
            currentPage = 1; // Reset to first page when filters are cleared
            filterTable();
        });

        // Initial display
        filterTable();
    });
</script>