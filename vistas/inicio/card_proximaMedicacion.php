<?php
    try {
        // Consulta para obtener la próxima medicación
        $sqlProximaMedicacion = "SELECT nombre_medicacion.nom_med, horarios_medicacion.hora_med, usuario_medicacion.fecha_toma
                                FROM usuario_medicacion
                                JOIN nombre_medicacion ON usuario_medicacion.id_medicacion = nombre_medicacion.id_nom_med
                                JOIN horarios_medicacion ON usuario_medicacion.id_horario = horarios_medicacion.id_horario
                                WHERE usuario_medicacion.id_usuarios = ?
                                AND horarios_medicacion.hora_med > NOW()  -- Solo si el horario es posterior a la hora actual
                                ORDER BY horarios_medicacion.hora_med ASC
                                LIMIT 1";

        $stmt = $conn->prepare($sqlProximaMedicacion);
        $stmt->bind_param('i', $id_usuarios);
        $stmt->execute();
        $resultProximaMedicacion = $stmt->get_result();

        if ($resultProximaMedicacion->num_rows > 0) {
            $rowProxima = $resultProximaMedicacion->fetch_assoc();
            $horaProximaMedicacion = $rowProxima["hora_med"];
            $fechaToma = $rowProxima["fecha_toma"];
        }

        // Consulta para obtener la lista de medicación programada de la misma fecha que la próxima medicación
        $sqlMedicacionProgramada = "SELECT nombre_medicacion.nom_med, usuario_medicacion.cant_mg
                                FROM usuario_medicacion
                                JOIN nombre_medicacion ON usuario_medicacion.id_medicacion = nombre_medicacion.id_nom_med
                                JOIN horarios_medicacion ON usuario_medicacion.id_horario = horarios_medicacion.id_horario
                                WHERE usuario_medicacion.id_usuarios = ?
                                AND usuario_medicacion.fecha_toma = ?
                                AND horarios_medicacion.hora_med = ?
                                ORDER BY horarios_medicacion.hora_med ASC";

        $stmt = $conn->prepare($sqlMedicacionProgramada);
        $stmt->bind_param('iss', $id_usuarios, $fechaToma, $horaProximaMedicacion);
        $stmt->execute();
        $resultMedicacionProgramada = $stmt->get_result();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
?>
<style>
    #photo {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #camera {
            display: none;
        }

        label {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
         
        }
</style>

<div class="card" id="proximaMedicacion">
    <div class="card-content">
        <h2>Próxima Medicación</h2>
        <?php
            if (isset($horaProximaMedicacion)) {
                echo "<p><i class='fas fa-clock'></i> " . $horaProximaMedicacion . "</p>";
            } else {
                echo "<p>No hay medicación programada.</p>";
            }
        ?>
    </div>
</div>
<div class="card" id="subirFotoCard">
    <div class="card-content">
        <h2>Subir Foto</h2>
        <button id="subirFotoButton" class="btn btn-primary">Subir Foto</button>
    </div>
</div>
<div class="modal fade" id="medicationModal" tabindex="-1" role="dialog" aria-labelledby="medicationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicationModalLabel"><i class="fas fa-prescription-bottle"></i> Detalles de Medicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="medicationModalBody">
                <!-- Botón para subir imagen -->
                <div class="text-center">
                  <label id="subirImagenButton" for="camera">Abrir cámara <i class="fa-solid fa-camera-retro"></i></label>
                </div>   
                <!-- Formulario para procesar los datos -->
                <form action="controller/procesar_medicacion_save.php" method="POST" enctype="multipart/form-data">
                <?php
                        if (isset($id_operadores)) {
                            // Consulta para obtener el nombre del operador
                            $query = "SELECT nombre_o, apellido_o FROM operadores WHERE id_operadores = $id_operadores";
                            $result = $conn->query($query);

                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $nombre_operador = $row['nombre_o'] . ' ' . $row['apellido_o'];
                                
                                echo "<input type='hidden' name='id_operadores'  value='$id_operadores'>";
                                echo "<br><h5><span class='badge bg-success text-white text-center'> Responsable: $nombre_operador</span><h5>";
                                
                            } else {
                                echo '<p>No se encontró el operador con el ID proporcionado.</p>';
                            }
                            $conn->close();
                        } else {
                            echo '<p>El ID de operador no está definido.</p>';
                        }
                ?>      
                <input type="file" name="imagenSubida" id="inputSubirImagen" style="display: none;">
                <div id="imagenMostrada" style="display: none;"><br>
                    <h4>Imagen de medicación:</h4>
                    <div id="imagenSubidaContainer">
                        <img id="imagenSubida" src="" alt="Imagen Subida">
                    </div>
                </div>                   
                    <!-- <input type="hidden" name="id_horario" value="" id="idHorarioInput"> -->
                    <input type="hidden" name="id_casas" value="<?php echo $id_casas ?>">
                    <input type="hidden" name="id_usuarios" id="idUsuarioHidden" value="<?php echo $id_usuarios ?>">

                   <br>
                  <select name="id_horario" class="form-control" required>
                        <option value="" disabled selected>Seleccione un horario</option>
                        <?php
                            include_once 'bd/conexion.php';

                            // Establecer conexión a la base de datos
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            // Verificar si la conexión se estableció correctamente
                            if ($conn->connect_error) {
                                die("Error al conectar a la base de datos: " . $conn->connect_error);
                            }

                            // Consulta para obtener los horarios de la tabla 'horarios_medicacion'
                            $query = "SELECT id_horario, hora_med FROM horarios_medicacion";
                            $result = $conn->query($query);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id_horario = $row['id_horario'];
                                    $hora_med = $row['hora_med'];
                                    echo '<option value="' . $id_horario . '">' . $hora_med . '</option>';
                                }
                            } else {
                                echo '<option value="">No se encontraron horarios</option>';
                            }

                            // Cierra la conexión a la base de datos
                            $conn->close();
                        ?>
                    </select>
                    <div class="modal-footer" id="botonesGuardarCancelar" style="display: none;">
                    <button type="submit"  class="btn btn-primary">Guardar</button><br>
                    <button id="cancelarSubida" class="btn btn-danger">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalMedicacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Lista de Medicación Programada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
            if ($resultMedicacionProgramada->num_rows > 0) {
                while ($rowMedicacion = $resultMedicacionProgramada->fetch_assoc()) {
                    echo "<p><i class='fas fa-prescription'></i> " . $rowMedicacion["nom_med"] . " (mg): " . $rowMedicacion["cant_mg"] . "</p>";
                }
            } else {
                echo "<p>No hay medicación programada para esta hora.</p>";
            }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- Otros botones del pie del modal si es necesario -->
      </div>
    </div>
  </div>
</div>

<script>
    // Agrega un controlador de eventos al botón "Subir Foto" para mostrar el modal
    document.getElementById("subirFotoButton").addEventListener("click", function() {
        // Muestra el modal utilizando JavaScript
        $('#medicationModal').modal('show');
    });

    // Agrega un controlador de eventos al botón "Subir Imagen"
    document.getElementById("subirImagenButton").addEventListener("click", function() {
        // Activa el input oculto para seleccionar un archivo
        document.getElementById("inputSubirImagen").click();
    });

    // Agrega un controlador de eventos al input de selección de imagen
    document.getElementById("inputSubirImagen").addEventListener("change", function() {
        const imagenSeleccionada = this.files[0];
        if (imagenSeleccionada) {
            const imagenURL = URL.createObjectURL(imagenSeleccionada);

            // Muestra el área de la imagen y establece la imagen seleccionada
            document.getElementById("imagenMostrada").style.display = "block";
            document.getElementById("imagenSubida").src = imagenURL;

            // Muestra los botones "Guardar" y "Cancelar"
            document.getElementById("botonesGuardarCancelar").style.display = "block";
        }
    });

    // Agrega un controlador de eventos al botón "Cancelar"
    document.getElementById("cancelarSubida").addEventListener("click", function() {
        // Puedes ocultar el área de imagen y los botones si se cancela la subida
        document.getElementById("imagenMostrada").style.display = "none";
        document.getElementById("botonesGuardarCancelar").style.display = "none";
        // También puedes restablecer el valor del input file si es necesario
        document.getElementById("inputSubirImagen").value = "";
    });
    $(document).ready(function(){
    $("#proximaMedicacion").click(function(){
      $("#modalMedicacion").modal('show');
    });
  }); 
</script>