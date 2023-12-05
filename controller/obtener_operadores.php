<?php
// obtener_operadores.php

// Simular una conexión a la base de datos
include_once '../bd/conexion.php';

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Obtener el ID del usuario desde la solicitud GET
if (isset($_GET['id_usuarios'])) {
    $idUsuario = intval($_GET['id_usuarios']); // Convertir a entero para seguridad

    // Consulta para obtener los operadores relacionados con el usuario
    $query = "SELECT o.id_operadores, o.nombre_o, o.apellido_o
              FROM operadores o
              INNER JOIN viviendas v ON o.id_operadores = v.id_operadores
              WHERE v.id_usuarios = $idUsuario";

    $result = $conn->query($query);

    $operadores = array();

    // Obtener los resultados de la consulta
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $operador = array(
                'id_operadores' => $row['id_operadores'],
                'nombre_operador' => $row['nombre_o'] . ' ' . $row['apellido_o']
            );
            $operadores[] = $operador;
        }
    }

    // Cerrar el resultado y la conexión a la base de datos
    $result->close();
    $conn->close();

    // Devolver la lista de operadores en formato JSON
    header('Content-Type: application/json');
    echo json_encode($operadores);
} else {
    // Si no se proporcionó el ID del usuario, enviar una respuesta de error
    header("HTTP/1.0 400 Bad Request");
    echo "ID de usuario no proporcionado.";
}
?>