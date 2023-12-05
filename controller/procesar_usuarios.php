<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['nombre_u']) && isset($_POST['apellido_u'])) {
        // Obtener los datos enviados desde el formulario
        $nombre_u = $_POST['nombre_u'];
        $apellido_u = $_POST['apellido_u'];

        // Procesar los datos y realizar la inserción en la base de datos
        // Aquí debes agregar tu código para realizar la inserción en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la inserción con mysqli
        include "../bd/conexion.php";
        
        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Crear la consulta de inserción
        $consulta = "INSERT INTO usuarios (nombre_u, apellido_u) VALUES ('$nombre_u', '$apellido_u')";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $consulta)) {
            // Si la consulta se ejecuta con éxito, redireccionar a index.php con un mensaje de éxito
            $mensaje = "Usuario agregado exitosamente.";
            header("Location: ../usuarios.php?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a index.php con un mensaje de error
            $mensaje = "Error al agregar el Usuario: " . mysqli_error($conexion);
            header("Location: ../usuarios.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a index.php con un mensaje de error
        $mensaje = "Error: faltan datos para agregar el usuario.";
        header("Location: ../usuarios.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a index.php
    header("Location: ../usuarios.php");
    exit;
}
?>