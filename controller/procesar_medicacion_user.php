<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuarios = $_POST["id_usuarios"];
    $id_medicacion = $_POST["id_medicacion"];
    $cant_mg = $_POST["cant_mg"];
    $id_horario = $_POST["id_horario"];
    $fecha_toma = $_POST["fechaSeleccionada"]; // Obtén la fecha seleccionada del formulario

    // Conexión a la base de datos (asegúrate de incluir tu archivo de conexión 'conexion.php')
    include_once '../bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Consulta SQL para insertar los datos en la tabla
    $sql = "INSERT INTO usuario_medicacion (id_usuarios, id_medicacion, cant_mg, id_horario, fecha_toma) 
            VALUES ('$id_usuarios', '$id_medicacion', '$cant_mg', '$id_horario', '$fecha_toma')";

    if ($conn->query($sql) === TRUE) {
        // Datos guardados correctamente, muestra una alerta de éxito
        $mensaje = "Datos guardados correctamente";
        $alertClass = 'alert-success'; // Éxito: alerta verde
        header("Location: ../plan_farmacologico.php?id_usuarios=$id_usuarios&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
    } else {
        // Error al guardar los datos, muestra una alerta de error
        $mensaje = "Error al guardar los datos: " . $conn->error;
        $alertClass = 'alert-danger'; // Error: alerta roja
        header("Location: ../plan_farmacologico.php?id_usuarios=$id_usuarios&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
    }

    $conn->close();
}
?>