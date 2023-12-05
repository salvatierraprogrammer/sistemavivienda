<?php
// Inicializar $id_casas en null o con cualquier otro valor por defecto que desees
$id_casas = $_POST['id_casas'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['id_casas']) && isset($_POST['id_usuarios'])) {
        // Obtener los datos enviados desde el formulario
        $id_casas = $_POST['id_casas'];
        $id_usuarios = $_POST['id_usuarios'];

        // Procesar los datos y realizar la inserción en la base de datos
        // Aquí debes agregar tu código para realizar la inserción en la base de datos con los datos proporcionados

        // Ejemplo de cómo podrías realizar la inserción con mysqli
        include "../bd/conexion.php";
        
        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Crear la consulta de inserción
        $consulta = "INSERT INTO viviendas (id_casas, id_usuarios) VALUES ('$id_casas', '$id_usuarios')";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $consulta)) {
            // Si la consulta se ejecuta con éxito, redireccionar a viviendas.php con un mensaje de éxito
            $mensaje = "Usuario agregado exitosamente a la vivienda.";
            header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a viviendas.php con un mensaje de error
            $mensaje = "Error al agregar el Usuario a la vivienda: " . mysqli_error($conexion);
            header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar a viviendas.php con un mensaje de error
        $mensaje = "Error: faltan datos para agregar el usuario a la vivienda.";
        header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a viviendas.php
    header("Location: ../buttons.php");
    exit;
}
?>