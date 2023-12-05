<?php
if (isset($_POST['hora_med'])) {
    $hora_med = $_POST['hora_med'];

    // Agrega aquí la conexión a la base de datos y las demás configuraciones
    include_once '../bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }
    $consulta = "INSERT INTO horarios_medicacion (hora_med) VALUES (?)";
    $stmt = $conn->prepare($consulta);

    if ($stmt) {
        // Vincular el parámetro de la consulta con el dato proporcionado
        $stmt->bind_param("s", $hora_med);  // "s" indica que es un parámetro de tipo cadena (string)

        if ($stmt->execute()) {
            header("Location: ../horarios_med.php?id&mensaje=" . urlencode("Horario guardado exitosamente."));
            exit;
        } else {
            header("Location: ../horarios_med.php?id&mensaje=" . urlencode("Error al guardar los datos: " . $stmt->error));
            exit;
        }

        $stmt->close();
    } else {
        header("Location: ../horarios_med.php?id&mensaje=" . urlencode("Error al preparar la consulta: " . $conn->error));
        exit;
    }

    $conn->close();
} else {
    header("Location: ../buttons.php?id&mensaje=" . urlencode("Error: faltan datos para realizar la inserción."));
    exit;
}
?>