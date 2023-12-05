<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_casas = $_POST['id_casas'];
    $id_operadores = $_POST['id_operadores'];
    $ubicacion = $_POST['ubicacion'];
    $id_usuarios = $_POST['id_usuarios'];

    // Configura la zona horaria
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    // Valor de hora_retiro
    $hora_retiro = '00:00:00';

    // Obtiene la fecha y hora actual en el formato de MySQL
    $fecha_ingreso = date('Y-m-d H:i:s'); // Formato: 'YYYY-MM-DD HH:MM:SS'

    // Aquí puedes usar los datos en tu proceso de registro de asistencia
    // Por ejemplo, puedes insertar los datos en la tabla de asistencia en la base de datos
    include_once '../bd/conexion.php';

    // Establecer conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Insertar los datos en la tabla de asistencia
    $sql = "INSERT INTO asistencia (id_vivienda, id_operadores, asistencia, ubicacion, hora_retiro, fecha_ingreso) VALUES (?, ?, 'Asistio', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $id_casas, $id_operadores, $ubicacion, $hora_retiro, $fecha_ingreso);

    if ($stmt->execute()) {
        $mensaje = "Asistencia registrada exitosamente";

        setcookie("id_casas", $id_casas, time() + 604800, "/");
        setcookie("id_operadores", $id_operadores, time() + 604800, "/");
        setcookie("id_usuarios", $id_usuarios, time() + 604800, "/");

        header("Location: ../panel_operador.php?mensaje=" . urlencode($mensaje));
        exit;
    } else {
        echo "Error al registrar la asistencia: " . $stmt->error;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
}
?>