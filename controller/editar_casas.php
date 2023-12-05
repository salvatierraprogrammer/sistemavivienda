<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['id_casas']) && isset($_POST['nombre_c']) && isset($_POST['direccion_c']) && isset($_POST['telefono_c'])) {
        // Obtener los datos enviados desde el formulario
        $id_casas = $_POST['id_casas'];
        $nombre_c = $_POST['nombre_c'];
        $direccion_c = $_POST['direccion_c'];
        $telefono_c = $_POST['telefono_c'];

        // Procesar los datos y realizar la actualización en la base de datos
        // Aquí debes agregar tu código para realizar la actualización en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la actualización con mysqli
        include "../bd/conexion.php";
        
        $conexion = new mysqli($servername, $username, $password, $dbname);
        // Crear la consulta de actualización
        $consulta = "UPDATE casas SET nombre_c='$nombre_c', direccion_c='$direccion_c', telefono_c='$telefono_c' WHERE id_casas=$id_casas";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $consulta)) {
            // Si la consulta se ejecuta con éxito, redireccionar a index.php con un mensaje de éxito
            $mensaje = "Actualizado Casa correctamente.";
            header("Location: ../index.php?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a index.php con un mensaje de error
            $mensaje = "Error al actualizar la Casa: " . mysqli_error($conexion);
            header("Location: ../index.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a index.php con un mensaje de error
        $mensaje = "Error: faltan datos para la actualización.";
        header("Location: ../index.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a index.php
    header("Location: ../index.php");
    exit;
}
?>