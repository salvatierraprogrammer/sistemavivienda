<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_operadores = $_POST["id_operadores"];
    $rol = $_POST["rol"];

    // Incluir el archivo de conexión a la base de datos
    include_once '../bd/conexion.php';

    // Conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para actualizar el rol en la base de datos
    $consulta = "UPDATE users SET rol = ? WHERE id_operadores = ?";
    
    // Preparar la sentencia y vincular los parámetros
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("si", $rol, $id_operadores);

    // Ejecutar la sentencia preparada
    if ($stmt->execute()) {
        // Operación exitosa
        $mensaje = "Rol del operador actualizado exitosamente.";
        header("Location: ../op.php?mensaje=" . urlencode($mensaje));
        exit;
    } else {
        // Error al ejecutar la sentencia
        $mensaje = "Error al actualizar el rol del operador: " . $stmt->error;
        header("Location: ../op.php?mensaje=" . urlencode($mensaje));
        exit;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // Redirigir o mostrar un mensaje de error en caso de que se intente acceder a esta página directamente
    echo "Acceso no permitido.";
}
?>