<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor MySQL no se encuentra en localhost
$username = "root"; // Reemplaza con el nombre de usuario de tu base de datos
$password = ""; // Reemplaza con la contraseña de tu base de datos
$dbname = "vivienda"; // Reemplaza con el nombre de tu base de datos

// Crear una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
