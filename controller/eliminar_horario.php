<?php
//Eliminar Casas
$id_horario = $_POST['id_horario'];

// Procesar eliminar datos
include "../bd/conexion.php";

// Variable para almacenar el mensaje de resultado
$mensaje = ""; 
$conexion = mysqli_connect($servername, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conexion) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }
// Consulta para eliminar la casa con el id_casas proporcionado
$consulta = "DELETE FROM horarios_medicacion WHERE id_horario = '$id_horario'";

// Ejecutar la consulta para eliminar la casa
if (mysqli_query($conexion, $consulta)) {
    // Si la consulta se ejecuta con éxito, mostrar mensaje de éxito y redireccionar a index.php
    $mensaje = "Horario eliminado exitosamente.";
} else {
    // Si hay algún error en la consulta, mostrar mensaje de error y redireccionar a index.php
    $mensaje = "Error al eliminar el horario: " . mysqli_error($conexion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Redireccionar a index.php con el mensaje de resultado utilizando la variable GET "mensaje"
header("Location: ../horarios_med.php?mensaje=" . urlencode($mensaje));
exit; // Asegurarse de que el script termine después de la redirección
?>