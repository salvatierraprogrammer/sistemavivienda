<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['id_usuarios']) && isset($_POST['nombre_u']) && isset($_POST['apellido_u'])) {
        // Obtener los datos enviados desde el formulario
        $id_usuarios = $_POST['id_usuarios'];
        $nombre_u = $_POST['nombre_u'];
        $apellido_u = $_POST['apellido_u'];

        // Procesar los datos y realizar la actualización en la base de datos
        // Aquí debes agregar tu código para realizar la actualización en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la actualización con mysqli
        include "../bd/conexion.php";
        
        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Crear la consulta de actualización
        $consulta = "UPDATE usuarios SET nombre_u='$nombre_u', apellido_u='$apellido_u' WHERE id_usuarios='$id_usuarios'";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $consulta)) {
            // Si la consulta se ejecuta con éxito, redireccionar a usuarios.php con un mensaje de éxito
            $mensaje = "Usuario actualizado exitosamente.";
            header("Location: ../usuarios.php?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a usuarios.php con un mensaje de error
            $mensaje = "Error al actualizar el Usuario: " . mysqli_error($conexion);
            header("Location: ../usuarios.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a usuarios.php con un mensaje de error
        $mensaje = "Error: faltan datos para actualizar el usuario.";
        header("Location: ../usuarios.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a usuarios.php
    header("Location: ../usuarios.php");
    exit;
}
?>