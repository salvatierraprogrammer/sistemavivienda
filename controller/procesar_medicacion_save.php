<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_horario = $_POST["id_horario"];
    $id_casas = $_POST["id_casas"];
    $id_usuarios = $_POST["id_usuarios"];
    $id_operadores = $_POST["id_operadores"];

    // Verificar si se ha cargado una imagen
    if (isset($_FILES['imagenSubida']) && $_FILES['imagenSubida']['error'] === UPLOAD_ERR_OK) {
        // Ruta de destino para guardar la imagen (ajusta la ruta según tu estructura de directorios)
        $rutaDestino = "../img/img_medicacion/" . $_FILES['imagenSubida']['name'];

        // Mover la imagen desde la ubicación temporal al destino final
        if (move_uploaded_file($_FILES['imagenSubida']['tmp_name'], $rutaDestino)) {
            // Conexión a la base de datos (coloca esto al principio del script)
            include_once '../bd/conexion.php';
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Error al conectar a la base de datos: " . $conn->connect_error);
            }

            // Preparar la consulta SQL para insertar los datos junto con el nombre del archivo
            $stmt = $conn->prepare("INSERT INTO registro_medicacion (id_horario, id_casas, id_operadores, id_usuarios, foto) VALUES (?, ?, ?, ?, ?)");

            // Vincular los parámetros y sus tipos
            $stmt->bind_param("iiiss", $id_horario, $id_casas, $id_operadores, $id_usuarios, $_FILES['imagenSubida']['name']);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Datos e imagen insertados correctamente en la base de datos

                // Redireccionar a la página deseada con un mensaje de éxito
                $mensaje = "Datos guardados correctamente";
                $alertClass = 'alert-success'; // Éxito: alerta verde
                header("Location: ../panel_operador.php?id_usuarios=$id_usuarios&id_casas=$id_casas&id_operadores=$id_operadores&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
                exit; // Terminar el script después de la redirección
            } else {
                // Error al insertar datos en la base de datos

                // Redireccionar a la página deseada con un mensaje de error
                $mensaje = "Error al guardar los datos";
                $alertClass = 'alert-danger'; // Error: alerta roja
                header("Location: ../panel_operador.php?id_usuarios=$id_usuarios&id_operadores=$id_operadores&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
                exit; // Terminar el script después de la redirección
            }

            // Cerrar la conexión y el statement
            $stmt->close();
            $conn->close();
        } else {
            echo "Error al mover la imagen a la ubicación final.<br>";
        }
    } else {
        echo "No se cargó una imagen válida.<br>";
    }
} else {
    echo "No se ha enviado ninguna solicitud POST.<br>";
}
?>