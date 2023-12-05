<?php
//Eliminar Casas
$id_operadores = $_POST['id_operadores'];

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
$consulta = "DELETE FROM operadores WHERE id_operadores = '$id_operadores'";

// Ejecutar la consulta para eliminar la casa
if (mysqli_query($conexion, $consulta)) {
    // Si la consulta se ejecuta con éxito, mostrar mensaje de éxito y redireccionar a index.php
    $mensaje = "Operador eliminada exitosamente.";
} else {
    // Si hay algún error en la consulta, mostrar mensaje de error y redireccionar a index.php
    $mensaje = "Error al eliminar la Operador: " . mysqli_error($conexion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Redireccionar a index.php con el mensaje de resultado utilizando la variable GET "mensaje"
header("Location: ../op.php?mensaje=" . urlencode($mensaje));
exit; // Asegurarse de que el script termine después de la redirección
?>