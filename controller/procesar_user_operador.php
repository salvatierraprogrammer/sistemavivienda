<?php
// Verificar si se enviaron los datos necesarios
if (isset($_POST['id_operadores']) && isset($_POST['id_usuarios']) && isset($_POST['id_casas'])) {
    // Obtener los datos enviados desde el formulario
    $id_operadores = $_POST['id_operadores'];
    $id_usuarios = $_POST['id_usuarios'];
    $id_casas = $_POST['id_casas'];

    // Aquí debes agregar tu código para realizar la inserción en la tabla viviendas con los datos proporcionados
    // Asegúrate de realizar la conexión a la base de datos y utilizar sentencias preparadas para prevenir ataques de inyección de SQL

    // Ejemplo de inserción utilizando sentencia preparada
    include_once '../bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Crear la consulta de inserción con sentencia preparada
    $consulta = "INSERT INTO viviendas (id_operadores, id_usuarios, id_casas) VALUES (?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($consulta);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        // Vincular los parámetros de la consulta con los datos proporcionados
        $stmt->bind_param("iii", $id_operadores, $id_usuarios, $id_casas);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la consulta se ejecuta con éxito, redireccionar a la página deseada con un mensaje de éxito
            header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode("Datos guardados exitosamente."));
            exit;
        } else {
            // Si hay algún error en la consulta, redireccionar a la página deseada con un mensaje de error
            header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode("Error al guardar los datos: " . $conn->error));
            exit;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Si hubo un error en la preparación de la consulta, redireccionar a la página deseada con un mensaje de error
        header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode("Error al preparar la consulta: " . $conn->error));
        exit;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se enviaron todos los datos necesarios, redireccionar a la página deseada con un mensaje de error
    header("Location: ../buttons.php?id=$id_casas&mensaje=" . urlencode("Error: faltan datos para realizar la inserción."));
    exit;
}
?>