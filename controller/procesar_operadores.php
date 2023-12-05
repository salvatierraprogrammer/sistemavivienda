<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['nombre_o']) && isset($_POST['apellido_o']) && isset($_POST['email_o']) && isset($_POST['telefono_o'])) {
        // Obtener los datos enviados desde el formulario
        $nombre_o = $_POST['nombre_o'];
        $apellido_o = $_POST['apellido_o'];
        $email_o = $_POST['email_o'];
        $telefono_o = $_POST['telefono_o'];

        // Procesar los datos y realizar la inserción en la base de datos
        // Aquí debes agregar tu código para realizar la inserción en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la inserción con sentencias preparadas
        include "../bd/conexion.php";

        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Verificar si la conexión se estableció correctamente
        if ($conexion->connect_error) {
            die("Error al conectar a la base de datos: " . $conexion->connect_error);
        }

        // Crear la consulta de inserción con sentencia preparada
        $consulta = "INSERT INTO operadores (nombre_o, apellido_o, email_o, telefono_o) VALUES (?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $conexion->prepare($consulta);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros de la consulta con los datos proporcionados
            $stmt->bind_param("ssss", $nombre_o, $apellido_o, $email_o, $telefono_o);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Si la consulta se ejecuta con éxito, redireccionar a op.php con un mensaje de éxito
                $mensaje = "Operador agregado exitosamente.";
                header("Location: ../op.php?mensaje=" . urlencode($mensaje));
                exit;
            } else {
                // Si hay algún error en la consulta, redireccionar a op.php con un mensaje de error
                $mensaje = "Error al agregar el Operador: " . $stmt->error;
                header("Location: ../op.php?mensaje=" . urlencode($mensaje));
                exit;
            }

            // Cerrar la sentencia
            $stmt->close();
        } else {
            // Si hubo un error en la preparación de la consulta, redireccionar a op.php con un mensaje de error
            $mensaje = "Error al preparar la consulta: " . $conexion->error;
            header("Location: ../op.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a op.php con un mensaje de error
        $mensaje = "Error: faltan datos para agregar el operador.";
        header("Location: ../op.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a op.php
    header("Location: ../op.php");
    exit;
}
?>
