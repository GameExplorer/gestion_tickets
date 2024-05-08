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
    echo "<th></th> ";
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
        //echo "<button class='tableButtons' onclick=\"viewTicketDetails(" . $row["id_ticket"] . ")\">DETALLES</button>";
        echo '<form action="view_ticket.php" method="post">' .
            '<input type="hidden" name="ticket_id" value="' . $row["id_ticket"] . '">' .
            '<button type="submit" class=\'tableButtons\'>DETALLES</button>' .
            '</form>';
        //non logged -users shouldn't see this button and the status select button
        if (isset($_SESSION['loggedin'])) {

            echo "<select class='status-select'>";
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
$conn->close();
?>