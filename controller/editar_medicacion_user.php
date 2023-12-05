<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados por el formulario
    $id_user_medicacion = $_POST['id_user_merdicacion']; // Cambiado el nombre de la variable aquí
    $id_medicacion = $_POST['id_medicacion'];
    $cant_mg = $_POST['cant_mg'];
    $id_horario = $_POST['id_horario'];
    $id_usuarios = $_POST['id_usuarios'];
     echo $id_horario;
    // Incluir el archivo de conexión a la base de datos
    include_once '../bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Realizar la actualización de datos en la base de datos
    $query = "UPDATE usuario_medicacion SET id_usuarios = ?, id_medicacion = ?, cant_mg = ?, id_horario = ? WHERE id_user_merdicacion = ?"; // Cambiado el nombre de la columna aquí

    // Preparar la consulta
    $stmt = $conn->prepare($query);

    // Enlazar los parámetros
    $stmt->bind_param("iiiii", $id_usuarios, $id_medicacion, $cant_mg, $id_horario, $id_user_medicacion);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // La actualización fue exitosa, puedes redireccionar o mostrar un mensaje de éxito
        $mensaje = "Medicación editada para el usuario ";
        $alertClass = 'alert-warning'; // Éxito: alerta verde
    } else {
        // Hubo un error en la actualización, puedes mostrar un mensaje de error o manejarlo de otra manera
        $mensaje = "Error al guardar los datos: " . $stmt->error;
        $alertClass = 'alert-danger'; // Error: alerta roja
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn->close();
    
    // Redirigir de nuevo a la página plan_farmacologico.php con el mensaje y la clase de alerta
    header("Location: ../plan_farmacologico.php?id_usuarios=$id_usuarios&mensaje=" . urlencode($mensaje) . "&alertClass=$alertClass");
    exit;
} else {
    // Manejar el caso en el que no se haya enviado el formulario por POST
    echo "El formulario no se ha enviado por el método POST.";
}
?>