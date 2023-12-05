<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once 'bd/conexion.php';
// Realiza una consulta a la base de datos para obtener el nombre de la casa
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}
// Consulta para obtener las últimas fechas de medicación agregadas
$query_ultimas_fechas = "SELECT DISTINCT DATE(um.fecha_toma) as fecha
                        FROM usuario_medicacion um
                        WHERE um.id_usuarios = $id_usuarios
                        ORDER BY um.fecha_toma DESC
                        LIMIT 1";

$resultado_ultimas_fechas = mysqli_query($conn, $query_ultimas_fechas);

if ($resultado_ultimas_fechas && mysqli_num_rows($resultado_ultimas_fechas) > 0) {
    $fila_ultimas_fechas = mysqli_fetch_assoc($resultado_ultimas_fechas);
    $ultima_fecha = $fila_ultimas_fechas['fecha'];

    // Consulta para obtener los horarios de medicación de la última fecha para el usuario
    $query_proximos_horarios = "SELECT um.id_usuarios, hm.hora_med, nm.nom_med, um.cant_mg, um.id_horario
                                FROM usuario_medicacion um
                                INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                                INNER JOIN nombre_medicacion nm ON um.id_medicacion = nm.id_nom_med
                                WHERE um.id_usuarios = $id_usuarios AND DATE(um.fecha_toma) = '$ultima_fecha'
                                ORDER BY hm.hora_med ASC";

    $resultado_proximos_horarios = mysqli_query($conn, $query_proximos_horarios);

    if ($resultado_proximos_horarios && mysqli_num_rows($resultado_proximos_horarios) > 0) {
        $horarios_mostrados = array();
        while ($fila_proximo_horario = mysqli_fetch_assoc($resultado_proximos_horarios)) {
            $hora_horario = $fila_proximo_horario['hora_med'];
            $id_horario = $fila_proximo_horario['id_horario'];

            // Si el horario no se ha mostrado antes, crea un nuevo elemento en el array
            if (!array_key_exists($hora_horario, $horarios_mostrados)) {
                $horarios_mostrados[$hora_horario] = array(
                    'id_horario' => $id_horario,
                    'medicaciones' => array()
                );
            }
            // Agrega la medicación al array correspondiente al horario
            $horarios_mostrados[$hora_horario]['medicaciones'][] = array(
                'nom_med' => $fila_proximo_horario['nom_med'],
                'cant_mg' => $fila_proximo_horario['cant_mg']
            );
        }
        ?>

        <div class="card">
            <div class="card-content">
                <h2>Horarios de Medicación</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Turno</th>
                            <th>Horario</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($horarios_mostrados as $hora_horario => $data) {
                            $id_horario = $data['id_horario'];
                            $medicaciones = $data['medicaciones'];
                            $hora_actual = date('H:i'); // Hora actual en formato HH:MM
                            $hora_pasada = strtotime($hora_horario) < strtotime($hora_actual);

                            // Comparar la hora con los rangos de turnos
                            $turno = '';
                            if (strtotime($hora_horario) >= strtotime('01:00') && strtotime($hora_horario) < strtotime('12:00')) {
                                $turno = 'Mañana';
                            } elseif (strtotime($hora_horario) >= strtotime('12:00') && strtotime($hora_horario) < strtotime('13:00')) {
                                $turno = 'Mediodía';
                            } elseif (strtotime($hora_horario) >= strtotime('13:00') && strtotime($hora_horario) < strtotime('20:00')) {
                                $turno = 'Tarde';
                            } else {
                                $turno = 'Noche';
                            }
                        ?>
                        <tr>
                            <td><?php echo $turno; ?></td>
                            <td><?php echo $hora_horario; ?></td>
                            <td>
                                <?php
                                if ($hora_pasada) {
                                    echo '(Pasado)';
                                    echo '<i class="bi bi-x-circle-fill text-danger"></i>';
                                } else {
                                    echo '(Próxima)';
                                    echo '<i class="bi bi-clock text-warning"></i>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <a class="btn btn-success" href="medicacion.php?id_usuarios=<?php echo $id_usuarios; ?>&id_casas=<?php echo $id_casas; ?>&id_operadores=<?php echo $id_operadores; ?>">Ver detalles</a>
            </div>
        </div>
    <?php
    } else {
        echo '<p>No se encontraron horarios de medicación para la última fecha</p>';
    }
} else {
    echo '<p>No se encontraron fechas de medicación para este usuario</p>';
}
?>