<?php
$id_user_merdiacion = $_POST['id_user_merdiacion'];
$id_usuarios = $_POST['id_usuarios'];


// Incluir la configuración de la base de datos (conexión)
include_once '../bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}
// Realizar la consulta de eliminación
$sql = "DELETE FROM usuario_medicacion WHERE id_user_merdicacion = $id_user_merdiacion";

if (mysqli_query($conn, $sql)) {
    // La eliminación se realizó con éxito
    $mensaje = "Medicación eliminada exitosamente";
} else {
    // Hubo un error al eliminar
    $mensaje = "Error al eliminar la medicación: " . mysqli_error($conn);
}
// Agregar la clase 'alert-danger' si hay un error
$alertClass = (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-danger';

// Redirigir de nuevo a la página plan_farmacologico.php con el mensaje
$mensaje = "Medicacion eliminda para el usuario ";
header("Location: ../plan_farmacologico.php?id_usuarios=$id_usuarios&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
exit;


?>