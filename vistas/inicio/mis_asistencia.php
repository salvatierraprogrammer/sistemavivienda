<?php require_once "vistas/parte_superior.php"; ?>

<?php
// Verificar si 'id_operadores' está definido en la URL
$id_operadores = isset($_GET['id_operadores']) ? $_GET['id_operadores'] : null;
$id_casas = $_GET['id_casas'];

// Procesar el formulario de búsqueda por fecha
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_date = $_POST['selected_date'];

    // Validar y formatear la fecha (puedes agregar más validaciones según tus necesidades)
    $selected_date = date("Y-m-d", strtotime($selected_date));

    // Consulta SQL para buscar por fecha
    $consultaAsistenciaOperador = "SELECT * FROM asistencia WHERE id_operadores = $id_operadores AND id_vivienda = $id_casas AND DATE(fecha_ingreso) = '$selected_date'";

    // URL para el enlace de cancelar búsqueda
    $cancelarBusquedaURL = "?id_operadores=$id_operadores&id_casas=$id_casas";
} else {
    // Consulta original sin filtro por fecha
    $consultaAsistenciaOperador = "SELECT * FROM asistencia WHERE id_operadores = $id_operadores AND id_vivienda = $id_casas";

    // URL para el enlace de cancelar búsqueda
    $cancelarBusquedaURL = "?id_operadores=$id_operadores&id_casas=$id_casas";
}
?>
<style>
      .btn {
    padding: 9px 15px; /* Adjust as needed */
    font-size: 7px; /* Adjust as needed */
    margin-bottom: 7px;
 }
</style>
<div class="container">
    <!-- Tu código HTML aquí -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="dateForm" action="" method="POST">
                    <label for="start">Buscar por fecha:</label>
                    <input type="date" id="start" name="selected_date" value="" min="2018-01-01" max="2030-12-31" />
                    <button type="submit" class="btn-success"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <a href="<?php echo $cancelarBusquedaURL; ?>" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></a>
                </form>
            </div>
        </div>  
        <?php
            include_once 'bd/conexion.php';
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Verificar si la conexión se estableció correctamente
            if ($conn->connect_error) {
                die("Error al conectar a la base de datos: " . $conn->connect_error);
            }

            // Utilizar la consulta específica según si se ha enviado una fecha en el formulario
            $resultadoAsistenciaOperador = $conn->query($consultaAsistenciaOperador);

            $resultadosPorPagina = 30; // Cantidad de registros por página
            $totalRegistros = $resultadoAsistenciaOperador->num_rows;
            $totalPaginas = ceil($totalRegistros / $resultadosPorPagina);
           
            // Obtén el número de página actual desde la URL (por ejemplo, ?pagina=2)
            $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            
            // Calcula el índice de inicio para la consulta LIMIT
            $indiceInicio = ($paginaActual - 1) * $resultadosPorPagina;
            
            // Consulta SQL con LIMIT para la paginación, ordenando por id_asistencia en orden descendente
            $consultaAsistenciaOperador = "$consultaAsistenciaOperador ORDER BY id_asistencia DESC LIMIT $indiceInicio, $resultadosPorPagina";
            $resultadoAsistenciaOperador = $conn->query($consultaAsistenciaOperador);

            // Mostrar las tarjetas de usuarios en la vivienda aquí
            echo '<h2 class="text-center">Mis asistencias</h2>';
            echo '<div class="card-container d-flex flex-wrap justify-content-center">';

            while ($rowAsistencia = $resultadoAsistenciaOperador->fetch_assoc()) {
                $fechaHoraAsistencia = htmlspecialchars($rowAsistencia['fecha_ingreso']);
                $horaRetiro = htmlspecialchars($rowAsistencia['hora_retiro']);

                list($fechaAsistencia, $horaAsistencia) = explode(' ', $fechaHoraAsistencia);

                echo '<div class="card mb-3" style="margin: 3px;">';
                echo '<div class="card-body">';
                echo '<p class="card-title"><i class="fa-solid fa-calendar-days"></i> Fecha: ' . $fechaAsistencia . ' </p>';

                if ($horaRetiro === "00:00:00") {
                    echo '<p><i class="fa-solid fa-business-time"></i> Hora de Ingreso: ' . $horaAsistencia . '</p>';
                    echo '<p><i class="fa-solid fa-business-time"></i> Estado: <span class="badge badge-success">Activo</span></p>';
                } else {
                    echo '<p><i class="fa-solid fa-business-time"></i> Hora de Ingreso: ' . $horaAsistencia . '</p>';
                    echo '<p><i class="fa-solid fa-business-time"></i> Hora de Retiro: ' . $horaRetiro . '</p>';
                }

                echo '</div>';
                echo '</div>';
            }

            echo '</div>';
            // Liberar el resultado
            $resultadoAsistenciaOperador->free_result();
            // Cerrar la conexión
            $conn->close();
        ?>
    </div>
</div>
<?php require_once "vistas/parte_inferior.php" ?>