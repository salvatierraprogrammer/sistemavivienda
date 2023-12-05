<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer.php';
require 'PHPMailer-master/SMTP.php';
require 'PHPMailer-master/Exception.php';

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
// Verificar si el operador está activo
$consultaActivo = "SELECT hora_retiro FROM asistencia WHERE id_operadores = ? ORDER BY id_asistencia DESC LIMIT 1";
$stmtActivo = $conn->prepare($consultaActivo);
$stmtActivo->bind_param("i", $id_operadores);

if ($stmtActivo->execute()) {
    $resultadoActivo = $stmtActivo->get_result();

    if ($resultadoActivo && $resultadoActivo->num_rows > 0) {
        $rowActivo = $resultadoActivo->fetch_assoc();
        $horaRetiroUltimaAsistencia = $rowActivo['hora_retiro'];

        $activo = ($horaRetiroUltimaAsistencia === "00:00:00");
    }
}

if ($activo) {
    echo '<p>El operador está activo.</p>';
    
    // Consulta para obtener el correo del operador
    $consultaCorreo = "SELECT email_o FROM operadores WHERE id_operadores = ?";
    $stmtCorreo = $conn->prepare($consultaCorreo);
    $stmtCorreo->bind_param("i", $id_operadores);

    if ($stmtCorreo->execute()) {
        $resultadoCorreo = $stmtCorreo->get_result();

        if ($resultadoCorreo && $resultadoCorreo->num_rows > 0) {
            $rowCorreo = $resultadoCorreo->fetch_assoc();
            $correoOperador = $rowCorreo['email_o'];
            echo "El correo electrónico del operador con ID $id_operadores es: $correoOperador";
        } else {
            echo "No se encontró un operador con el ID $id_operadores.";
        }
    } else {
        echo "Error en la consulta SQL: " . $stmtCorreo->error;
    }

    // Mostrar horario de medicación 
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    // Consulta para obtener las últimas fechas de medicación agregadas
    $query_ultimas_fechas = "SELECT DISTINCT DATE(um.fecha_toma) as fecha
                            FROM usuario_medicacion um
                            WHERE um.id_usuarios = ?
                            ORDER BY um.fecha_toma DESC
                            LIMIT 1";
    $stmtFechas = $conn->prepare($query_ultimas_fechas);
    $stmtFechas->bind_param("i", $id_usuarios);

    if ($stmtFechas->execute()) {
        $resultado_ultimas_fechas = $stmtFechas->get_result();

        if ($resultado_ultimas_fechas && $resultado_ultimas_fechas->num_rows > 0) {
            $fila_ultimas_fechas = $resultado_ultimas_fechas->fetch_assoc();
            $ultima_fecha = $fila_ultimas_fechas['fecha'];

            // Consulta para obtener los horarios de medicación de la última fecha para el usuario
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
                    $correoEnviado = false; // Variable para evitar el envío múltiple de correos

                    while ($fila_proximo_horario = $resultado_proximos_horarios->fetch_assoc()) {
                        $hora_horario = $fila_proximo_horario['hora_med'];

                        // Calcula el tiempo restante hasta el horario de medicación
                        $hora_actual = date('H:i'); // Hora actual en formato HH:MM
                        
                        if ($hora_horario === $hora_actual) {
                            if (!$correoEnviado) {
                                // El horario coincide con la hora actual, envía un correo al operador
                
                                // Crear una instancia de PHPMailer
                                $mail = new PHPMailer(true);
                            
                                try {
                                    // Configuración del servidor SMTP
                                    $mail->isSMTP();
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->Username = 'salvatierraprogrammer@gmail.com'; // Cambia esto a tu dirección de correo
                                    $mail->Password = 'tjbqczxotfuwroub'; // Cambia esto a tu contraseña
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                    $mail->Port = 465;
                            
                                    // Configuración del remitente y destinatario
                                    $mail->setFrom('salvatierraprogrammer@gmail.com', 'Vivienda Asistida');
                                    $mail->addAddress($correoOperador, 'Nombre del Operador');
                            
                                    // Contenido del correo
                                    $mail->isHTML(true);
                                    $mail->Subject = 'Recordatorio de medicación para el usuario';
                                    $mail->Body = '<p>Es hora de tomar la medicación del usuario.</p>';
                            
                                    // Enviar el correo
                                    $mail->send();
                                    echo 'Correo enviado al operador.';
                                    $correoEnviado = true; // Establecer la variable a true para evitar envíos adicionales
                                } catch (Exception $e) {
                                    echo "Error al enviar el correo: {$mail->ErrorInfo}";
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



