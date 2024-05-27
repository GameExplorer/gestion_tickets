<?php
// PHP code in department_listing.php
include 'connection.php';

// Fetch departments from the database
$sql = "SELECT id_departamento, nombre_departamento FROM departamentos WHERE id_departamento != 0 AND disabled = 0";
$result = $conn->query($sql);

// Check if query was successful
if ($result === false) {
    // If there was an error in the query, output the error message
    echo "Error: " . $conn->error;
} else {
    // Initialize an empty array to store departments
    $departments = array();

    // Fetch each row and add it to the departments array
    while($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }

    // Output the departments array as JSON
    echo json_encode($departments);
}

// Close the database connection
$conn->close();