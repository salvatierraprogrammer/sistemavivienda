<?php
// Procesar guardar datos
include "../bd/conexion.php";

// Variable para almacenar el mensaje de resultado
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombreCasa = $_POST["nombre_c"];
    $direccionCasa = $_POST["direccion_c"];
    $telefonoCasa = $_POST["telefono_c"];

    // Validar los datos (puedes agregar más validaciones según tus necesidades)

    // Conectar a la base de datos
    $conexion = mysqli_connect($servername, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conexion) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }

    // Escapar los datos para evitar inyección de SQL
    $nombreCasa = mysqli_real_escape_string($conexion, $nombreCasa);
    $direccionCasa = mysqli_real_escape_string($conexion, $direccionCasa);
    $telefonoCasa = mysqli_real_escape_string($conexion, $telefonoCasa);

    // Consulta para agregar una nueva casa
    $consulta = "INSERT INTO casas (nombre_c, direccion_c, telefono_c) VALUES ('$nombreCasa', '$direccionCasa', '$telefonoCasa')";

    // Después de ejecutar la consulta para agregar una nueva casa
if (mysqli_query($conexion, $consulta)) {
    // Si la consulta se ejecuta con éxito, mostrar mensaje de éxito y redireccionar a index.php
    $mensaje = "Casa agregada exitosamente.";
} else {
    // Si hay algún error en la consulta, mostrar mensaje de error y redireccionar a index.php
    $mensaje = "Error al agregar la Casa: " . mysqli_error($conexion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Redireccionar a index.php con el mensaje de resultado utilizando la variable GET "mensaje"
header("Location: ../index.php?mensaje=" . urlencode($mensaje));
exit; // Asegurarse de que el script termine después de la redirección
}
?>