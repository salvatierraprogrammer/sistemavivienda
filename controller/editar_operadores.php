<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['id_operadores']) && isset($_POST['nombre_o']) && isset($_POST['apellido_o']) && isset($_POST['email_o']) && isset($_POST['telefono_o'])) {
        // Obtener los datos enviados desde el formulario
        $id_operadores = $_POST['id_operadores'];
        $nombre_o = $_POST['nombre_o'];
        $apellido_o = $_POST['apellido_o'];
        $email_o = $_POST['email_o'];
        $telefono_o = $_POST['telefono_o'];

        // Procesar los datos y realizar la actualización en la base de datos
        // Aquí debes agregar tu código para realizar la actualización en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la actualización con mysqli
        include "../bd/conexion.php";
        
        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Crear la consulta de actualización
        $consulta = "UPDATE operadores SET nombre_o='$nombre_o', apellido_o='$apellido_o', email_o='$email_o', telefono_o='$telefono_o' WHERE id_operadores='$id_operadores'";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $consulta)) {
            // Si la consulta se ejecuta con éxito, redireccionar a op.php con un mensaje de éxito
            $mensaje = "Operador actualizado exitosamente.";
            header("Location: ../op.php?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a op.php con un mensaje de error
            $mensaje = "Error al actualizar el Operador: " . mysqli_error($conexion);
            header("Location: ../op.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a op.php con un mensaje de error
        $mensaje = "Error: faltan datos para actualizar el operador.";
        header("Location: ../op.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a op.php
    header("Location: ../op.php");
    exit;
}
?>