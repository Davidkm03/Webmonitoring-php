<?php
$servername = "localhost";
$username = "root";
$password = "Juancho2024#";
$dbname = "webmonitor";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>