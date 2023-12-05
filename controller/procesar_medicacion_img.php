<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_horario = $_POST["id_horario"];
    $id_casas = $_POST["id_casas"];
    $id_usuarios = $_POST["id_usuarios"];
    $id_operadores = $_POST["id_operadores"];
    $imagen_base64 = $_POST['imagen_base64'];

    // Generar un nombre único para la imagen basado en la fecha y hora actual
    $currentDate = new DateTime();
    $nombreImagen = 'photo_' . $currentDate->format('YmdHis') . '.jpg'; // Cambia la extensión a JPG

    // Definir la ruta de destino para la imagen en el servidor
    $directorioDestino = "../img/img_medicacion"; // Cambia esta ruta según tu estructura de directorios
    $rutaDestino = $directorioDestino . '/' . $nombreImagen;

    // Guardar la imagen en el servidor
    if (file_put_contents($rutaDestino, base64_decode($imagen_base64))) {
     
        // La imagen se guardó con éxito

        // Ahora puedes insertar los datos en la base de datos
        include_once '../bd/conexion.php';
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error al conectar a la base de datos: " . $conn->connect_error);
        }

        // Preparar la consulta SQL para insertar los datos junto con el nombre del archivo
        $stmt = $conn->prepare("INSERT INTO registro_medicacion (id_horario, id_casas, id_operadores, id_usuarios, foto) VALUES (?, ?, ?, ?, ?)");

        // Vincular los parámetros
        $stmt->bind_param("iiiss", $id_horario, $id_casas, $id_operadores, $id_usuarios, $nombreImagen);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Datos e imagen insertados correctamente en la base de datos

            // Redireccionar a la página deseada con un mensaje de éxito
            $mensaje = "Datos guardados correctamente";
            $alertClass = 'alert-success'; // Éxito: alerta verde
            header("Location: ../medicacion.php?id_usuarios=$id_usuarios&id_casas=$id_casas&id_operadores=$id_operadores&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
            exit; // Terminar el script después de la redirección
        } else {
            // Error al insertar datos en la base de datos

            // Redireccionar a la página deseada con un mensaje de error
            $mensaje = "Error al guardar los datos";
            $alertClass = 'alert-danger'; // Error: alerta roja
            header("Location: ../medicacion.php?id_usuarios=$id_usuarios&id_operadores=$id_operadores&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
            exit; // Terminar el script después de la redirección
        }

        // Cerrar la conexión y el statement
        $stmt->close();
        $conn->close();
    } else {
        echo "Error al guardar la imagen en el servidor.<br>";
    }
} else {
    echo "No se ha enviado ninguna solicitud POST.<br>";
}
?>