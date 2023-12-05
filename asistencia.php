<?php
require_once "vistas/parte_superior.php";
include_once 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

if (isset($_POST['id_usuarios']) && isset($_POST['id_operadores']) && isset($_POST['id_casas'])) {
    $id_usuarios = $_POST['id_usuarios'];
    $id_operadores = $_POST['id_operadores'];
    $id_casas = $_POST['id_casas'];
} elseif (isset($_GET['id_usuarios']) && isset($_GET['id_operadores']) && isset($_GET['id_casas'])) {
    $id_usuarios = $_GET['id_usuarios'];
    $id_operadores = $_GET['id_operadores'];
    $id_casas = $_GET['id_casas'];
} else {
    echo "ID de usuarios no definido.";
    exit;
}

$consulta_operador = "SELECT id_operadores FROM operadores WHERE id_operadores = $id_operadores";
$resultado_operador = $conn->query($consulta_operador);

if ($resultado_operador && $resultado_operador->num_rows > 0) {
    $data_operador = $resultado_operador->fetch_assoc();
    $id_operador_seleccionado = $data_operador['id_operadores'];

    $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $registros_por_pagina = 10;

    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    $consulta_asistencia = "SELECT id_asistencia, asistencia, ubicacion, fecha_ingreso, hora_retiro, fecha_fin_jornada, ubicacion_salida, nombre_dispositivo
        FROM asistencia
        WHERE id_operadores = $id_operador_seleccionado
        AND id_vivienda = $id_casas
        ORDER BY fecha_ingreso DESC
        LIMIT $registros_por_pagina
        OFFSET $offset";

    $resultado_asistencia = $conn->query($consulta_asistencia);
}
?>

<div class="container">
    <?php
    if ($resultado_asistencia && $resultado_asistencia->num_rows > 0) {
        $consulta_nombre_operador = "SELECT CONCAT(nombre_o, ' ', apellido_o) AS nombre_completo FROM operadores WHERE id_operadores = $id_operadores";
        $resultado_nombre_operador = $conn->query($consulta_nombre_operador);

        $consulta_nombre_casa = "SELECT nombre_c FROM casas WHERE id_casas = $id_casas";
        $resultado_nombre_casa = $conn->query($consulta_nombre_casa);

        if ($resultado_nombre_operador && $resultado_nombre_operador->num_rows > 0) {
            $data_nombre_operador = $resultado_nombre_operador->fetch_assoc();
            $nombre_operador = $data_nombre_operador['nombre_completo'];
        } else {
            $nombre_operador = "Nombre de operador no encontrado";
        }

        if ($resultado_nombre_casa && $resultado_nombre_casa->num_rows > 0) {
            $data_nombre_casa = $resultado_nombre_casa->fetch_assoc();
            $nombre_casa = $data_nombre_casa['nombre_c'];
        } else {
            $nombre_casa = "Nombre de casa no encontrado";
        }
        echo "<h2><i class='fa-solid fa-house-chimney'></i> $nombre_casa / Operador: $nombre_operador</h2>";
        echo "<h2><br>  </h2>";
    } else {
        echo "<p>Este operador aún no ha ingresado.</p>";
    }
    ?>

    <div class="row">
        <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
                <table id="tablaPersonas" class="table table-striped table-bordered table-condensed" style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center"><i class="fas fa-calendar-day"></i> Fecha</th>
                            <th class="text-center"><i class='fas fa-clock'></i>  Ingreso</th>
                            <th class="text-center"><i class='fas fa-clock'></i> Salida</th>
                            <th class="text-center">Ubicacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultado_asistencia as $asistencia) {
                            $id_asistencia = $asistencia['id_asistencia'];
                            $fecha_ingreso = $asistencia['fecha_ingreso'];
                            $hora_retiro = $asistencia['hora_retiro'];
                            $ubicacion = $asistencia['ubicacion'];
                            $fecha_fin_jornada = $asistencia['fecha_fin_jornada'];
                            $ubicacion_salida = $asistencia['ubicacion_salida'];
                            $nombre_dispositivo = $asistencia['nombre_dispositivo'];
                            list($fecha, $hora_entrada) = explode(' ', $fecha_ingreso); 
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $id_asistencia; ?></td>
                                <td class="text-center"><?php echo $fecha; ?></td>
                                <td class="text-center"><i class="fa-solid fa-person-walking-arrow-right"></i> <?php echo $hora_entrada; ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($hora_retiro === '00:00:00') {
                                        echo '<span class="badge badge-success">Activo</span>';
                                    } else {
                                        echo "<i class='fa-solid fa-person-walking-dashed-line-arrow-right' style='transform: scaleX(-1);'></i>
                                        $hora_retiro ";
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                <div class='btn-group'>
                                    <button type="button" class="btn btn-info btnUbicacion"
                                        data-toggle="modal"
                                        data-target="#ubicacionModal"
                                        data-id="<?php echo $id_asistencia; ?>"
                                        data-nombre="<?php echo $nombre_operador; ?>"
                                        data-ubicacion="<?php echo $ubicacion; ?>"
                                        data-fecha="<?php echo $fecha_ingreso; ?>"
                                        data-horaretiro="<?php echo $hora_retiro; ?>"
                                        data-fechafinjornada="<?php echo $fecha_fin_jornada; ?>"
                                        data-ubicacionsaldia="<?php echo $ubicacion_salida; ?>"
                                        data-nombredispositivo="<?php echo $nombre_dispositivo ?>"
                                    >
                                        <i class="fa-solid fa-location-dot"></i>
                                        Entrada
                                    </button>
                                    <?php
                                    if ($hora_retiro !== '00:00:00') {
                                        echo '<button type="button" class="btn btn-success btnUbicacionSalida"
                                            data-toggle="modal"
                                            data-target="#ubicacionSalidaModal"
                                            data-id="' . $id_asistencia . '"
                                            data-nombre="' . $nombre_operador . '"
                                            data-fecha="' . $fecha_ingreso . '"
                                            data-horaretiro="' . $hora_retiro . '"
                                            data-fechafinjornada="' . $fecha_fin_jornada . '"
                                            data-ubicacionsaldia="' . $ubicacion_salida . '"
                                            data-nombredispositivo="' . $nombre_dispositivo . '"
                                        >
                                            <i class="fa-solid fa-location-dot"></i>
                                            Salida
                                        </button>';
                                    }
                                    ?>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            
                <?php
                        if ($resultado_asistencia && $resultado_asistencia->num_rows > 0) {
                            // Calcular el número total de páginas
                            $consulta_total_asistencias = "SELECT COUNT(*) as total FROM asistencia WHERE id_operadores = $id_operador_seleccionado AND id_vivienda = $id_casas";
                            $resultado_total_asistencias = $conn->query($consulta_total_asistencias);
                            $total_registros = 0;
                            
                            if ($resultado_total_asistencias && $resultado_total_asistencias->num_rows > 0) {
                                $data_total_asistencias = $resultado_total_asistencias->fetch_assoc();
                                $total_registros = $data_total_asistencias['total'];
                            }
                            
                            $total_paginas = ceil($total_registros / $registros_por_pagina);

                            // Definir el límite de páginas a mostrar
                            $limite_paginas = 5; // Puedes ajustar este número según tus necesidades

                            echo '<div class="text-center">';
                            echo '<ul class="pagination">';

                            if ($pagina_actual > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . ($pagina_actual - 1) . '&id_casas=' . $id_casas . '&id_operadores=' . $id_operadores . '&id_usuarios=' . $id_usuarios . '">Anterior</a></li>';
                            }

                            $inicio = max(1, $pagina_actual - floor($limite_paginas / 2));
                            $fin = min($inicio + $limite_paginas - 1, $total_paginas);

                            for ($pagina = $inicio; $pagina <= $fin; $pagina++) {
                                echo '<li class="page-item';
                                if ($pagina == $pagina_actual) {
                                    echo ' active';
                                }
                                echo '"><a class="page-link" href="?pagina=' . $pagina . '&id_casas=' . $id_casas . '&id_operadores=' . $id_operadores . '&id_usuarios=' . $id_usuarios . '">' . $pagina . '</a></li>';
                            }

                            if ($pagina_actual < $total_paginas) {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . ($pagina_actual + 1) . '&id_casas=' . $id_casas . '&id_operadores=' . $id_operadores . '&id_usuarios=' . $id_usuarios . '">Siguiente</a></li>';
                            }

                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
            </div>
        </div>
    </div></div>
          </div>
</div>
<!--FIN del contenido principal-->
<!-- Fin contenido -->
<?php
require_once "vistas/parte_inferior.php";
                
?>

<div class="modal fade" id="ubicacionSalidaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Contenido del modal (encabezado, cuerpo y pie) -->
            <div class="modal-body">
                <div id="modalMap" style="height: 300px;"></div>
                <hr>
                <h4 class="badge badge-secondary" style="font-size: 20px;">
                    <i class="fa-solid fa-user"></i>
                    <span id="modalOperadorNombre"></span>
                </h4>
                <hr>
                <!-- Card Aqui -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detalles de jornada finalizada</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fa-solid fa-calendar-day"></i> Fecha de Ingreso: <span id="modalFechaIngreso"></span>
                            </li> 
                            <li class="list-group-item">
                              <i class="fa-solid fa-business-time"></i> Fecha salida <span id="modalFechaFinJornada"></span>
                            </li>                          
                            <li class="list-group-item">
                            <i class='fa-solid fa-person-walking-dashed-line-arrow-right' style='transform: scaleX(-1);'></i> Hora de Retiro: <span id="modalHoraRetiro"></span> 
                            </li>
                            
                            <li class="list-group-item">
                                <i class="fa-solid fa-mobile-android"></i> Nombre del Dispositivo: <span id="modalNombreDispositivo"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>  
<div class="modal fade" id="ubicacionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Contenido del modal (encabezado, cuerpo y pie) -->
            <div class="modal-body">
                <div id="modalMap" style="height: 300px;"></div>
                <hr>
                <h4 class="badge badge-secondary" style="font-size: 20px;">
                    <i class="fa-solid fa-user"></i>
                    <span id="modalOperadorNombre"></span>
                </h4>
                <hr>
                <!-- Card Aqui -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detalles de la Asistencia</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fa-solid fa-calendar-day"></i> Fecha de Ingreso: <span id="modalFechaIngreso"></span>
                                
                            </li>
                            <li class="list-group-item">
                               
                                <i class="fa-solid fa-person-walking-arrow-right"></i> Horario: <span id="modalHoraIngreso"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
        function goBack() {
        window.history.back();
    }

    // Definir la función initMap
    function initMap() {
        // Declarar tus variables y lógica de inicialización del mapa aquí
        $(document).ready(function() {
            // Función para abrir el modal de ubicación
            function abrirModalUbicacion(idAsistencia, nombreOperador, fechaIngreso, ubicacion, horaretiro, fechafinjornada, ubicacionsaldia, nombredispositivo, modalId) {
        // Rellenar los elementos del modal con los datos obtenidos
        $(modalId + ' #modalIdAsistencia').text(idAsistencia);
        $(modalId + ' #modalOperadorNombre').text(nombreOperador);
        
        if (fechaIngreso) {
            // Dividir la fecha y la hora
            var [fecha, horaIngreso] = fechaIngreso.split(' ');

            // Mostrar las variables en los elementos correspondientes del modal
            $(modalId + ' #modalFechaIngreso').text(fecha);
            $(modalId + ' #modalHoraIngreso').text(horaIngreso);
        } else {
            $(modalId + ' #modalFechaIngreso').text("No disponible");
            $(modalId + ' #modalHoraIngreso').text("No disponible");
        }

        $(modalId + ' #modalHoraRetiro').text(horaretiro);
        $(modalId + ' #modalFechaFinJornada').text(fechafinjornada);
        $(modalId + ' #modalUbicacionSalida').text(ubicacionsaldia);
        $(modalId + ' #modalNombreDispositivo').text(nombredispositivo);

        var [lat, lng] = ubicacion.split(', ');
        var mapOptions = {
            center: new google.maps.LatLng(parseFloat(lat), parseFloat(lng)),
            zoom: 15,
        };
        var map = new google.maps.Map(document.querySelector(modalId + ' #modalMap'), mapOptions);

        var marker = new google.maps.Marker({
            position: { lat: parseFloat(lat), lng: parseFloat(lng) },
            map: map,
        });

        // Abrir el modal correspondiente
        $(modalId).modal('show');
    }

            // Manejar clic en botón de ubicación
            $('.btnUbicacion').click(function() {
                var idAsistencia = $(this).data('id');
                var nombreOperador = $(this).data('nombre');
                var fechaIngreso = $(this).data('fecha');
                var ubicacion = $(this).data('ubicacion');
                var horaretiro = $(this).data('horaretiro');
                var fechafinjornada = $(this).data('fechafinjornada');
                var ubicacionsaldia = $(this).data('ubicacionsaldia');
                var nombredispositivo = $(this).data('nombredispositivo');
                
                abrirModalUbicacion(idAsistencia, nombreOperador, fechaIngreso, ubicacion, horaretiro, fechafinjornada, ubicacionsaldia, nombredispositivo, '#ubicacionModal');
            });

            // Manejar clic en botón de ubicación de salida
            $('.btnUbicacionSalida').click(function() {
                var idAsistencia = $(this).data('id');
                var nombreOperador = $(this).data('nombre');
                var fechaIngreso = $(this).data('fecha');
                var horaretiro = $(this).data('horaretiro');
                var fechafinjornada = $(this).data('fechafinjornada');
                var ubicacionsaldia = $(this).data('ubicacionsaldia');
                var nombredispositivo = $(this).data('nombredispositivo');
                
                abrirModalUbicacion(idAsistencia, nombreOperador, fechaIngreso, ubicacionsaldia, horaretiro, fechafinjornada, ubicacionsaldia, nombredispositivo, '#ubicacionSalidaModal');
            });
        });
    }

    // Agregar el evento de carga para la API de Google Maps
    window.addEventListener("load", () => {
        const script = document.createElement("script");
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDOcMJoFTPGABxksOrd2vh9ab3nT-oMUAI&libraries=places&callback=initMap";
        document.body.appendChild(script);
    });
</script>















    
</div>
<!--FIN del cont principal-->

<!-- Fincontenido -->


   