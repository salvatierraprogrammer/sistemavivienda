<div class="card" id="">
    <div class="card-content">
        <h2>Opciones</h2>
        <br>
        <a class="btn btn-success" data-toggle="modal" data-target="#modalMisAsistencias">Mis asistencias</a>

        <div class="modal fade" id="modalMisAsistencias" tabindex="-1" role="dialog" aria-labelledby="modalMisAsistenciasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMisAsistenciasLabel">Mis Asistencias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                        <?php
                        include_once 'bd/conexion.php';
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        // Verificar si la conexión se estableció correctamente
                        if ($conn->connect_error) {
                            die("Error al conectar a la base de datos: " . $conn->connect_error);
                        }
                        
                        
                        $consultaAsistenciaOperador = "SELECT * FROM asistencia WHERE id_operadores = $id_operadores AND id_vivienda = $id_casas";
                        $resultadoAsistenciaOperador = $conn->query($consultaAsistenciaOperador);
                        $resultadosPorPagina = 5; // Cantidad de registros por página
                        $totalRegistros = $resultadoAsistenciaOperador->num_rows;
                        $totalPaginas = ceil($totalRegistros / $resultadosPorPagina);
                       
                        // Obtén el número de página actual desde la URL (por ejemplo, ?pagina=2)
                        $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                        
                        // Calcula el índice de inicio para la consulta LIMIT
                        $indiceInicio = ($paginaActual - 1) * $resultadosPorPagina;
                        
                        // Consulta SQL con LIMIT para la paginación, ordenando por id_asistencia en orden descendente
                        $consultaAsistenciaOperador = "SELECT * FROM asistencia WHERE id_operadores = $id_operadores AND id_vivienda = $id_casas ORDER BY id_asistencia DESC LIMIT $indiceInicio, $resultadosPorPagina";
                        $resultadoAsistenciaOperador = $conn->query($consultaAsistenciaOperador);
                        
                        // Mostrar las tarjetas de usuarios en la vivienda aquí
                        echo '<h2 class="text-center">Mis asistencias</h2>';
                        while ($rowAsistencia = $resultadoAsistenciaOperador->fetch_assoc()) {
                            $fechaHoraAsistencia = htmlspecialchars($rowAsistencia['fecha_ingreso']);
                            $horaRetiro = htmlspecialchars($rowAsistencia['hora_retiro']);
                            
                            list($fechaAsistencia, $horaAsistencia) = explode(' ', $fechaHoraAsistencia);
                        
                            echo '<div class="card mb-3">';
                            echo '<div class="card-body">';
                            echo '<p class="card-title"><i class="fa-solid fa-calendar-days"></i> Mis asistencias ' . $fechaAsistencia . ' </p>';
                            

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
                        // Liberar el resultado
                        $resultadoAsistenciaOperador->free_result();
                        // Cerrar la conexión
                        $conn->close();
                        ?>
                  
            </div>
            <div class="modal-footer">
                <a href="mis_asistencia.php?id_operadores=<?php echo $id_operadores ?>&id_casas=<?php echo $id_casas ?>" class="btn btn-success">Ver mas...</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
        
        <br>
        <!-- <a type="submit" class="btn btn-success"href="registro_med.php?id_usuarios=<?php echo $id_usuarios; ?>&id_casas=<?php echo $id_casas; ?>">Hiatorial medicacion</a>    -->
     
        
    </div>
</div>


