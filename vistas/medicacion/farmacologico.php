<?php require_once "vistas/parte_superior.php"; ?>

<?php 
    $mensaje = ""; // Inicializa la variable $mensaje

    // Verifica si hay un mensaje en la URL
    if (isset($_GET['mensaje'])) {
        $mensaje = $_GET['mensaje'];
    }


    if (isset($_POST['id_usuarios'])) { // Cambiado a $_GET si se espera obtenerlo de la URL
        $id_usuarios = $_POST['id_usuarios']; // Cambiado a $_GET si se espera obtenerlo de la URL
        // Resto de tu código aquí
    } else 
        // Manejo de error o redirección si $_GET['id_usuarios'] no está definido
    if (isset($_GET['id_usuarios'])) {
            $id_usuarios = $_GET['id_usuarios'];
        } else {
            echo "ID de usuarios no definido.";
            exit;
    }


    // Aquí debes realizar la consulta a la base de datos para obtener los detalles de los operadores según los IDs que recibiste en $operadores.
    include_once 'bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    $query_usuario = "SELECT nombre_u, apellido_u FROM usuarios WHERE id_usuarios = $id_usuarios";
    $resultado_usuario = mysqli_query($conn, $query_usuario);

    if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
        $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
        $nombre_usuario = $fila_usuario['nombre_u'] . ' ' . $fila_usuario['apellido_u'];
    } else {
        $nombre_usuario = "Usuario no encontrado";
    }
?> 

<div class="container">
    <h1><i class="fas fa-pills"></i> Plan Farmacologico de <?php echo $nombre_usuario ?></h1>
    <div class="row">
        <div class="col-lg-12">
        <div class="modal-footer">       
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-fw fa-plus"></i> Nuevo Plan Farmacologico
                <span id="nombreUsuario"></span>
        </button>    
        
        </div>
        </div>
        <?php if ($mensaje !== "") { ?>
    <div class="alert <?php echo isset($_GET['alertClass']) ? $_GET['alertClass'] : 'alert-danger alert-warning'; ?>" role="alert">
        <i class="fas fa-pills"></i>
        <?php echo $mensaje; ?>
        <!-- Botón de cierre (cruz) -->
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
        <div class="table-responsive">
        <?php
            $query_dates = "SELECT DISTINCT DATE(fecha_toma) as fecha FROM usuario_medicacion WHERE id_usuarios = $id_usuarios ORDER BY fecha_toma DESC";
            $result_dates = mysqli_query($conn, $query_dates);

            if ($result_dates && mysqli_num_rows($result_dates) > 0) {
                while ($row_dates = mysqli_fetch_assoc($result_dates)) {
                    $fecha = $row_dates['fecha'];
                    echo '<hr>';
                    echo "<h3><span class='badge bg-secondary text-white'><i class='fas fa-calendar-alt'></i> Fecha: $fecha</span></h3>";
                    
                    $query_medications = "SELECT nm.nom_med, um.cant_mg, hm.hora_med, um.id_user_merdicacion, um.id_medicacion, um.id_horario
                                        FROM usuario_medicacion um
                                        INNER JOIN nombre_medicacion nm ON um.id_medicacion = nm.id_nom_med
                                        INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                                        WHERE um.id_usuarios = $id_usuarios AND DATE(um.fecha_toma) = '$fecha'";
                    
                    $result_medications = mysqli_query($conn, $query_medications);

                    if ($result_medications && mysqli_num_rows($result_medications) > 0) {
                        echo '<div class="card">';
                        echo '<table class="table table-striped table-bordered table-condensed" style="width:100%">';
                        echo '<thead class="text-center">';
                        echo '<tr>';
                        echo '<th>Medicacion</th>';
                        echo '<th>Cant mg</th>';
                        echo '<th>Hora</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        while ($row_medication = mysqli_fetch_assoc($result_medications)) {
                            $id_user_merdiacion = $row_medication['id_user_merdicacion'];
                            $nom_med = $row_medication['nom_med'];
                            $cant_mg = $row_medication['cant_mg'];
                            $hora_med = $row_medication['hora_med'];
                            $id_horario = $row_medication['id_horario'];
                            $id_medicacion = $row_medication['id_medicacion'];
                            
                            echo "<tr>";
                            echo "<td class='text-center'>" . $nom_med . "</td>";
                            echo "<td class='text-center'>" . $cant_mg . "</td>";
                            echo "<td class='text-center'>" . $hora_med . "</td>";
                            echo "<td class='text-center'>
                                    <div class='btn-group'>
                                    <button type='button' class='btn btn-warning btnEditar' 
                                    data-toggle='modal' data-target='#modalEdit'
                                    data-id='$id_user_merdiacion'
                                    data-nommed='$nom_med'
                                    data-cantmg='$cant_mg'
                                    data-idhorario='$id_horario'
                                    data-idMedicacion='$id_medicacion'
                                    data-horamed='$hora_med'>
                                    <i class='fa-solid fa-pen-to-square'></i>
                                    <button class='btn btn-danger btnBorrar' data-toggle='modal' data-target='#modalConfirmarBorrar'
                                    data-id='$id_user_merdiacion'>
                                    <i class='fa-solid fa-trash-can'></i>
                                </button>
                                </td>";
                            echo "</tr>";
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo "<p>No se encontraron medicaciones para esta fecha.</p>";
                    }
                }
            } else {
                echo "<p>No se encontraron fechas de medicación para este usuario.</p>";
            }
    ?>

</div>
        </div>
    </div>
</div>
<!-- Modal de Confirmación para Borrar -->
<div class="modal fade" id="modalConfirmarBorrar" tabindex="-1" role="dialog" aria-labelledby="modalConfirmarBorrarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmarBorrarLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este registro?
            </div>
            <div class="modal-footer">
                <form action="controller/eliminar_medicacion_plan_farmacologi.php" method="post">
                <input type="hidden" name="id_user_merdiacion" id="inputIdUserMedicacion" value="">
                <input type="hidden" name="id_usuarios" id="inputIdUserMedicacion" value="<?php echo $id_usuarios ?>">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="sumbmit" class="btn btn-danger" id="btnConfirmarBorrar">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal para Editar Medicación -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Editar Medicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarMedicacion" action="controller/editar_medicacion_user.php" method="POST">
                    <input type="hidden" id="idEditarMedicacion" name="id_user_merdicacion" >
                    <div class="form-group">
                        <label for="editarNomMed">Nombre Medicación:</label>
                        <select class="form-control editarNomMed" name="id_medicacion">
                            <?php
                            // Fetch and loop through nombre_medicacion data
                            $query_medicaciones = "SELECT * FROM nombre_medicacion";
                            $result_medicaciones = mysqli_query($conn, $query_medicaciones);

                            while ($row_medicacion = mysqli_fetch_assoc($result_medicaciones)) {
                                $id_medicacion = $row_medicacion['id_nom_med'];
                                $nom_medicacion = $row_medicacion['nom_med'];

                                // Output option element with value and text
                                echo "<option value='$id_medicacion'>$nom_medicacion</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editarCantMg">Cantidad mg:</label>
                        <input type="text" class="form-control editarCantMg" id="editarCantMg" name="cant_mg">
                    </div>
                    <div class="form-group">
                        <label for="editarHoraMed">Hora:</label>
                        <select class="form-control editarHoraMed" name="id_horario">
                            <?php
                            // Fetch and loop through horarios_medicacion data
                            $query_horarios = "SELECT * FROM horarios_medicacion";
                            $result_horarios = mysqli_query($conn, $query_horarios);

                            while ($row_horario = mysqli_fetch_assoc($result_horarios)) {
                                $id_horario = $row_horario['id_horario'];
                                $hora_horario = $row_horario['hora_med'];

                                // Output option element with value and text
                                echo "<option value='$id_horario'>$hora_horario</option>";
                            }
                            ?>
                        </select>
                    </div>
                 <input type="hidden" name="id_usuarios" value="<?php echo $id_usuarios ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarMedicacion" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>





<!-- Modal nueva planilla plan farmacologico-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo plan farmacologico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
    <div class="modal-body">
         <form id="formPersonas" action="controller/procesar_medicacion_user.php" method="POST">
                <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre_u" class="col-form-label">Nombre de Usuario:</label>
                                <input type="text" class="form-control" id="nombre_u" value="<?php echo $nombre_usuario; ?>" readonly>
                                <input type="hidden" name="id_usuarios" value="<?php echo $id_usuarios ?>">
                            </div>
                                <hr>
                                <div class="form-group">
                                    <label for="fechaSeleccionada" class="col-form-label">Fecha de plan farmacologico:</label>
                                    <input type="date" class="form-control" id="fechaSeleccionada" name="fechaSeleccionada">
                                </div>
                                <div class="form-group">
                                    <label for="nombre_u" class="col-form-label">Nombre medicacion:</label>
                                    <select class="form-control" id="nom_med" name="id_medicacion">
                                        <?php
                                        // Incluir el archivo de conexión a la base de datos
                                        include "bd/conexion.php";

                                        // Establecer la conexión a la base de datos
                                        $conn = new mysqli($servername, $username, $password, $dbname);

                                        // Verificar si la conexión se estableció correctamente
                                        if ($conn->connect_error) {
                                            die("Error al conectar a la base de datos: " . $conn->connect_error);
                                        }

                                        // Ejecutar una consulta SQL para obtener los nombres de medicación
                                        $query = "SELECT id_nom_med, nom_med FROM nombre_medicacion";
                                        $resultado_nombres_medicacion = $conn->query($query);

                                        // Iterar a través de los nombres de medicación y crear opciones
                                        while ($fila_nombre_med = mysqli_fetch_assoc($resultado_nombres_medicacion)) {
                                            $id_nom_med = $fila_nombre_med['id_nom_med'];
                                            $nom_med = $fila_nombre_med['nom_med'];
                                            echo "<option value='$id_nom_med'>$nom_med</option>";
                                        }
                                       

                                        // Cerrar la conexión a la base de datos
                                        $conn->close();
                                        ?>
                                    </select>
                                </div>
                            <div class="form-group col-md-2">
                                <label for="cant_mg">Cantidad</label>
                                <input type="text" class="form-control" id="cant_mg" name="cant_mg" >
                            </div>
                            <div class="form-group">
                                <label for="hora_med" class="col-form-label">Hora:</label>
                                <select class="form-control" id="hora_med" name="id_horario">
                                    <?php
                                    // Incluir el archivo de conexión a la base de datos
                                    include "bd/conexion.php";

                                    // Establecer la conexión a la base de datos
                                    $conn = new mysqli($servername, $username, $password, $dbname);

                                    // Verificar si la conexión se estableció correctamente
                                    if ($conn->connect_error) {
                                        die("Error al conectar a la base de datos: " . $conn->connect_error);
                                    }

                                    // Ejecutar una consulta SQL para obtener los horarios de medicación
                                    $query = "SELECT id_horario, hora_med FROM horarios_medicacion";
                                    $resultado_horarios = $conn->query($query);

                                    // Iterar a través de los horarios y crear opciones
                                    while ($fila_horario = mysqli_fetch_assoc($resultado_horarios)) {
                                        $id_horario = $fila_horario['id_horario'];
                                        $hora_med = $fila_horario['hora_med'];
                                        echo "<option name='id_horario' value='$id_horario'>$hora_med</option>";
                                    }

                                    // Cerrar la conexión a la base de datos
                                    $conn->close();
                                    ?>
                                </select>
                            </div>                               
                      </div>
                      
     

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnGuardarPlan">Guardar plan farmacologico</button>
        </div>
        </form>
    </div>
  </div>
</div>


<script>
    $('.btnEditar').on('click', function(event) {
        var button = $(event.currentTarget);
        var idHorario = button.data('idhorario');
        var horaMed = button.data('horamed');
        
        console.log('idHorario:', idHorario); // Log the idHorario value
        
        var modal = $('#modalEdit');
        
        // Set the current hora_med and medicacion as the selected options
        modal.find('.editarHoraMed').val(idHorario);
        modal.find('.editarNomMed').val(button.data('idmedicacion')); // Selecciona la medicación
        
        modal.find('#idEditarMedicacion').val(button.data('id'));
        modal.find('#editarCantMg').val(button.data('cantmg'));
    });
</script>
<script>
    $(document).ready(function () {
        // Captura el ID del registro a eliminar cuando se hace clic en el botón "Eliminar"
        $(".btnBorrar").click(function () {
            var idBorrar = $(this).data('id');
            
            // Establece el ID en el campo oculto del formulario de confirmación
            $("#inputIdUserMedicacion").val(idBorrar);
        });

        // Resto de tu script existente
    });
</script>
 
<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   
