<?php
// Include the connection.php file to establish a connection to the database
include 'connection.php';

// Check if department parameter is provided
if (isset($_GET['department'])) {
    // Sanitize and validate the input
    $department = $_GET['department'];

    // Perform a query to fetch categories based on the department
    $sql = "SELECT nombre_categoria FROM categorias WHERE id_departamento = ? AND disabled = 0";

    // Assuming you are using prepared statements for security
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $department);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Initialize an array to store categories
    $categories = array();

    // Fetch categories and add them to the array
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['nombre_categoria'];
    }

    // Close the statement
    $stmt->close();

    // Output categories as JSON
    echo json_encode($categories);
} else {
    // Handle case where department parameter is not provided
    echo json_encode(array()); // Return an empty array
}