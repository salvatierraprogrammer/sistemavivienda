<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Argentina/Buenos_Aires');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_operadores = isset($_POST['id_operadores']) ? $_POST['id_operadores'] : null;
    $ubicacion_salida = isset($_POST['ubicacion_salida']) ? $_POST['ubicacion_salida'] : null;
    $nombre_dispositivo = isset($_POST['nombre_dispositivo']) ? $_POST['nombre_dispositivo'] : null;

    // Verificar si el ID de operador está presente
    if ($id_operadores !== null) {
        // Establecer conexión a la base de datos
        include_once '../bd/conexion.php'; // Asegúrate de incluir el archivo correcto
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar si la conexión se estableció correctamente
        if ($conn->connect_error) {
            die("Error al conectar a la base de datos: " . $conn->connect_error);
        }

        // Buscar el último id_asistencia del operador
        $sql = "SELECT MAX(id_asistencia) AS ultimo_id FROM asistencia WHERE id_operadores = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Error en la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $id_operadores);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $ultimo_id_asistencia = $row['ultimo_id'];

            // Obtener la hora actual y mantener la fecha_ingreso existente
            $hora_retiro = date('H:i:s');
            $fecha_fin_jornada = date('Y-m-d H:i:s'); // Fecha actual

            // Actualizar la hora de retiro, ubicación de salida, nombre del dispositivo y fecha_fin_jornada en el registro encontrado
            // Actualizar la hora de retiro, ubicación de salida, nombre del dispositivo y fecha_fin_jornada en el registro encontrado
    $sql = "UPDATE asistencia SET hora_retiro = ?, ubicacion_salida = ?, nombre_dispositivo = ?, fecha_fin_jornada = ? WHERE id_asistencia = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $hora_retiro, $ubicacion_salida, $nombre_dispositivo, $fecha_fin_jornada, $ultimo_id_asistencia);

            if ($stmt->execute()) {
                // Redirige al operador a una página de cierre de sesión o a donde desees
                header("Location: logout.php"); // Cambia "logout.php" al nombre de tu página de cierre de sesión
                exit();
            } else {
                echo "Error al registrar la hora de retiro, ubicación de salida, nombre del dispositivo y fecha_fin_jornada: " . $stmt->error;
            }
        } else {
            echo "No se encontró ningún registro de asistencia para este operador.";
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conn->close();
    } else {
        // Manejo de errores si falta el ID de operador en la solicitud POST
        echo "Falta el ID de operador en la solicitud POST.";
    }
} else {
    // Manejo de errores si la solicitud no es de tipo POST o no se envió un ID de operador
    echo "Esta página solo acepta solicitudes POST con un ID de operador.";
}
?>