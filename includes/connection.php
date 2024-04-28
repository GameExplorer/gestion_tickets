<?php
// Database connection
$host = 'localhost';
$dbuser = 'root';
$dbpassword = '';
$database = 'cu_gestion_tickets';

$conn = new mysqli($host, $dbuser, $dbpassword, $database);

if ($conn->connect_error) {
    die("Conexión fallada: " . $conn->connect_error);
}
