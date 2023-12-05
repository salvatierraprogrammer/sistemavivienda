<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['nom_med']) && isset($_POST['marca_med']) && isset($_POST['caja_comp'])) {
        // Obtener los datos enviados desde el formulario
        $nom_med = $_POST['nom_med'];
        $marca_med = $_POST['marca_med'];
        $caja_comp = $_POST['caja_comp'];

        // Procesar los datos y realizar la inserción en la base de datos
        // Aquí debes agregar tu código para realizar la inserción en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la inserción con mysqli
        include "../bd/conexion.php";
        
        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Crear la consulta de inserción
        $consulta = "INSERT INTO nombre_medicacion (nom_med, marca_med, caja_comp) VALUES ('$nom_med', '$marca_med', '$caja_comp')";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $consulta)) {
            // Si la consulta se ejecuta con éxito, redireccionar a index.php con un mensaje de éxito
            $mensaje = "Medicacion agregado exitosamente.";
            header("Location: ../medicacion_nom.php?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a index.php con un mensaje de error
            $mensaje = "Error al agregar el Usuario: " . mysqli_error($conexion);
            header("Location: ../medicacion_nom.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a index.php con un mensaje de error
        $mensaje = "Error: faltan datos para agregar el usuario.";
        header("Location: ../medicacion_nom.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a index.php
    header("Location: ../medicacion_nom.php");
    exit;
}
?>