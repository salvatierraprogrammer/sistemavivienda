<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer.php';
require 'PHPMailer-master/SMTP.php';
require 'PHPMailer-master/Exception.php';

// require 'vendor/pusher/pusher-php-server/src/Pusher.php';
require 'vendor/autoload.php'; // Incluye el cargador de Composer

$options = array(
    'cluster' => 'ap2',
    'useTLS' => true
);

$pusher = new Pusher\Pusher(
    '2f077a35246b79a8ac5f',
    '9f4b1bd0b38e89f26958',
    '1690825',
    $options
);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el operador está activo
$consultaActivo = "SELECT TIME(fecha_ingreso) AS hora_ingreso, hora_retiro FROM asistencia WHERE id_operadores = ? ORDER BY id_asistencia DESC LIMIT 1";
$stmtActivo = $conn->prepare($consultaActivo);
$stmtActivo->bind_param("i", $id_operadores);

if ($stmtActivo->execute()) {
    $resultadoActivo = $stmtActivo->get_result();

    if ($resultadoActivo && $resultadoActivo->num_rows > 0) {
        $rowActivo = $resultadoActivo->fetch_assoc();
        $horaIngreso = $rowActivo['hora_ingreso'];
        $horaRetiroUltimaAsistencia = $rowActivo['hora_retiro'];

        // echo "Hora de ingreso: " . $horaIngreso;
        // echo "Hora de retiro: " . $horaRetiroUltimaAsistencia;

        $activo = ($horaRetiroUltimaAsistencia === "00:00:00");
    }
}

$query_usuario = "SELECT nombre_u, apellido_u FROM usuarios WHERE id_usuarios = $id_usuarios";
$resultado_usuario = mysqli_query($conn, $query_usuario);

if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $nombre_usuario = $fila_usuario['nombre_u'] . ' ' . $fila_usuario['apellido_u'];
} else {
    $nombre_usuario = "Usuario no encontrado";
}

if ($activo) {
    echo '<p>El operador está activo.</p>';

    // Consulta para obtener el correo del operador
    $consultaCorreo = "SELECT nombre_o, apellido_o, email_o, telefono_o FROM operadores WHERE id_operadores = ?";
    $stmtCorreo = $conn->prepare($consultaCorreo);
    $stmtCorreo->bind_param("i", $id_operadores);

    if ($stmtCorreo->execute()) {
        $resultadoCorreo = $stmtCorreo->get_result();

        if ($resultadoCorreo && $resultadoCorreo->num_rows > 0) {
            $rowCorreo = $resultadoCorreo->fetch_assoc();
            $correoOperador = $rowCorreo['email_o'];
            $telefonoOperador = $rowCorreo['telefono_o'];
            $nombreOperador = $rowCorreo['nombre_o'];
            $apellidoOperador = $rowCorreo['apellido_o'];

            // echo "El correo electrónico del operador con ID $id_operadores es: $correoOperador";
        } else {
            echo "No se encontró un operador con el ID $id_operadores.";
        }
    } else {
        echo "Error en la consulta SQL: " . $stmtCorreo->error;
    }

    // Mostrar horario de medicación
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $query_ultimas_fechas = "SELECT DISTINCT DATE(um.fecha_toma) as fecha FROM usuario_medicacion um WHERE um.id_usuarios = ? ORDER BY um.fecha_toma DESC LIMIT 1";
    $stmtFechas = $conn->prepare($query_ultimas_fechas);
    $stmtFechas->bind_param("i", $id_usuarios);

    $fila_ultimas_fechas = null;

    if ($stmtFechas->execute()) {
        $resultado_ultimas_fechas = $stmtFechas->get_result();

        if ($resultado_ultimas_fechas && $resultado_ultimas_fechas->num_rows > 0) {
            $fila_ultimas_fechas = $resultado_ultimas_fechas->fetch_assoc();
            $ultima_fecha = $fila_ultimas_fechas['fecha'];

            $query_proximos_horarios = "SELECT um.id_usuarios, hm.hora_med, nm.nom_med, um.cant_mg, um.id_horario
                                    FROM usuario_medicacion um
                                    INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                                    INNER JOIN nombre_medicacion nm ON um.id_medicacion = nm.id_nom_med
                                    WHERE um.id_usuarios = ? AND DATE(um.fecha_toma) = ?
                                    ORDER BY hm.hora_med ASC";
            $stmtHorarios = $conn->prepare($query_proximos_horarios);
            $stmtHorarios->bind_param("is", $id_usuarios, $ultima_fecha);

            if ($stmtHorarios->execute()) {
                $resultado_proximos_horarios = $stmtHorarios->get_result();

                if ($resultado_proximos_horarios && $resultado_proximos_horarios->num_rows > 0) {
                    $correoEnviado = false;

                    while ($fila_proximo_horario = $resultado_proximos_horarios->fetch_assoc()) {
                        $hora_horario = $fila_proximo_horario['hora_med'];
                        $hora_actual = date('H:i');
                        $tolerancia = 1;

                        if (abs(strtotime($hora_horario) - strtotime($hora_actual)) <= 60 * $tolerancia) {
                            if (!$correoEnviado) {
                                $mail = new PHPMailer(true);

                                try {
                                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                    $mail->isSMTP();
                                    $mail->CharSet = 'UTF-8';
                                    $mail->Encoding = 'base64';
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->Username = 'salvatierraprogrammer@gmail.com';
                                    $mail->Password = 'tjbqczxotfuwroub';
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                    $mail->Port = 465;
                                    $mail->setFrom('salvatierraprogrammer@gmail.com', 'Vivienda Asistida');
                                    $mail->addAddress($correoOperador, ''.$nombreOperador .' '. $apellidoOperador .'');

                                    $mail->isHTML(true);
                                    $mail->Subject = 'Recordatorio de medicación para el usuario';
                                    $mail->Body = '<p>Es hora ' . $hora_horario . ' de tomar la medicación del usuario ' . $nombre_usuario . '. </p>';

                                    $mail->send();
                                    // echo 'Correo enviado al operador.';
                                    $correoEnviado = true;

                                    // Enviar notificación en tiempo real con Pusher
                                    $data = ['message' => 'Es hora ' . $hora_horario . ' de tomar la medicación del usuario ' . $nombre_usuario . '.'];
                                    $pusher->trigger('my-channel', 'my-event', $data);

                                } catch (Exception $e) {
                                    echo "Error al enviar el correo: {$e->getMessage()}";
                                }
                            }
                        }
                        echo '<p>Próxima medicación ' . $hora_horario . '</p>';
                    }
                } else {
                    echo '<p>No se encontraron horarios de medicación para la última fecha</p>';
                }
            } else {
                echo "Error en la consulta SQL: " . $stmtHorarios->error;
            }
        } else {
            echo '<p>No se encontraron fechas de medicación para este usuario</p>';
        }
    } else {
        echo "Error en la consulta SQL: " . $stmtFechas->error;
    }
} else {
    echo '<p>El operador no está activo.</p>';
}
?>