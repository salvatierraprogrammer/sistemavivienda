<?php
// Incluye el archivo de conexión a la base de datos
include_once '../bd/conexion.php';

// Obtiene el ID del usuario desde la solicitud GET
if (isset($_GET['id_usuarios'])) {
    $id_usuarios = $_GET['id_usuarios'];

    // Consulta SQL para obtener las últimas 5 entradas del historial de medicación
    $sql = "SELECT rm.fecha_hora, rm.foto, o.nombre_o, o.apellido_o
            FROM registro_medicacion rm
            INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
            WHERE rm.id_usuarios = $id_usuarios
            ORDER BY rm.fecha_hora DESC
            LIMIT 5";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Recorre los resultados y muestra cada entrada en el historial
        while ($row = $result->fetch_assoc()) {
            $fecha_hora = $row["fecha_hora"];
            $foto = "img/img_medicacion/" . $row["foto"];
            $nombre_operador = $row["nombre_o"];
            $apellido_operador = $row["apellido_o"];
            
            // Genera el HTML para mostrar cada entrada
            echo '<div class="card-flex">';
            echo '<div class="card-content-flex">';
            echo '<div class="info">';
            echo '<h2>Medicación</h2>';
            echo '<p><i class="fas fa-clock"></i><span> ' . $fecha_hora . '</span></p>';
            echo '<p>Responsable:<br> <span>' . $nombre_operador . ' ' . $apellido_operador . '</i></p>';
            echo '</div>';
            echo '<div class="image">';
            echo '<img id="img" src="' . $foto . '" alt="Medicación">';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<br>';

        }
    } else {
        // No se encontraron registros para el usuario
        echo 'No se encontró historial de medicación.';
    }
} else {
    echo 'ID de usuario no especificado.';
}

// Cierra la conexión a la base de datos
$conn->close();
?>