<?php require_once "vistas/parte_superior.php"?>
<?php
$mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable

// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
?>
<!-- Inicio contenido principal -->
<div class="container">
<h1> Registro de medicacion</h1>
<?php if ($mensaje !== "") { ?>
    <div class="alert <?php echo (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-warning'; ?>" role="alert">
        <?php echo $mensaje; ?>
        <!-- Botón de cierre (cruz) -->
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>
    <div class="row">
        <div class="col-lg-12">
        <div class="modal-footer">
           <!-- Botón para abrir el modal -->
       <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">Nuevo medicacion</button>
        </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
        <div class="card">
                <div class="card-header">
                    Lista de Medicaciones
                </div>
                <div class="card-body">
            <div class="table-responsive">
                <table id="tablaPersonas" class="table table-striped table-bordered table-condensed" style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Marca</th>
                            <th class="text-center">Caja de</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Incluir el archivo de conexión
                        include_once 'bd/conexion.php';
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Verificar si la conexión se estableció correctamente
                        if ($conn->connect_error) {
                            die("Error al conectar a la base de datos: " . $conn->connect_error);
                        }

                        // Realizar la consulta a la base de datos
                        $consulta = "SELECT id_nom_med, nom_med, marca_med, caja_comp FROM nombre_medicacion";
                        $resultado = $conn->query($consulta);

                        // Verificar si la consulta se ejecutó correctamente
                        if ($resultado) {
                            // Obtener los datos de la consulta en un arreglo asociativo
                            $data = $resultado->fetch_all(MYSQLI_ASSOC);

                            // Verificar si hay registros en el arreglo
                            if (count($data) > 0) {
                                foreach ($data as $dat) {
                                    // Obtener los datos de cada registro
                                    $id_nom_med = isset($dat['id_nom_med']) ? $dat['id_nom_med'] : 'N/A';
                                    $nom_med = isset($dat['nom_med']) ? $dat['nom_med'] : 'N/A';
                                    $marca_med = isset($dat['marca_med']) ? $dat['marca_med'] : 'N/A';
                                    $caja_comp = isset($dat['caja_comp']) ? $dat['caja_comp'] : 'N/A';
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $id_nom_med ?></td>
                                        <td class="text-center"><?php echo $nom_med ?></td>
                                        <td class="text-center"><?php echo $marca_med ?></td>
                                        <td class="text-center"><?php echo $caja_comp ?></td>
                                        
                                        <td class="text-center">
                                        <div class='btn-group'>
                                        <button type="button" class="btn btn-warning btnEditar" data-toggle="modal" data-target="#modalEditar"
                            data-id="<?php echo $id_nom_med ?>" data-nombre="<?php echo $nom_med ?>" data-marca="<?php echo $marca_med ?>"
                            data-caja="<?php echo $caja_comp ?>">Editar</button>               
                                        <button type="button" class="btn btn-danger btnEliminar" data-toggle="modal" data-target="#modalConfirmacion" data-id="<?php echo $id_nom_med ?>">Eliminar</button>                </td>
                                </div>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5">No se encontraron viviendas.</td>
                                </tr>
                                <?php
                            }
                        } else {
                            die("Error en la consulta: " . $conn->error);
                        }
                        ?>
                    </tbody>
                </table>   
                         
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button id="paginaAnterior" class="btn btn-primary mr-2"> < </button>
                        <button id="paginaSiguiente" class="btn btn-primary"> > </button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal para CRUD -->
<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nueva medicacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonas" action="controller/procesar_medicacion.php" method="POST">
                <div class="modal-body">

             

                    <div class="form-group">
                        <label for="nom_med" class="col-form-label">Nombre de medicacion:</label>
                        <input type="text" name="nom_med" class="form-control" id="nom_med">
                    </div>
                    <div class="form-group">
                        <label for="marca_med" class="col-form-label">Marca:</label>
                        <input type="text" name="marca_med" class="form-control" id="marca_med">
                    </div>
                    <div class="form-group">
                        <label for="caja_comp" class="col-form-label">Cantidad de comprimido:</label>
                        <input type="text" name="caja_comp" class="form-control" id="caja_comp">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar el usuario -->
                <form id="editarForm" action="controller/editar_medicacion.php" method="POST">
                    <!-- Campo oculto para el id_usuarios -->
                <div class="form-group">
                    <input type="hidden" name="id_nom_med" id="editIdNomMed" value="<?php echo $id_nom_med ?>">
                    <label for="nom_med" class="col-form-label">Nombre de medicación:</label>
                    <input type="text" name="nom_med" class="form-control" id="editNomMed" value="<?php echo $nom_med ?>">
                </div>
                <div class="form-group">
                    <label for="marca_med" class="col-form-label">Marca:</label>
                    <input type="text" name="marca_med" class="form-control" id="editMarcaMed" value="<?php echo $marca_med ?>">
                </div>
                <div class="form-group">
                    <label for="caja_comp" class="col-form-label">Cantidad de comprimido:</label>
                    <input type="text" name="caja_comp" class="form-control" id="editCajaComp" value="<?php echo $caja_comp ?>">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="editarForm" class="btn btn-warning">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar la medicación?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="controller/eliminar_medicacion.php" method="POST">
                    <!-- Campo oculto para el id_nom_med -->
                    <input type="hidden" name="id_nom_med" id="id_nom_med_eliminar">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEliminar" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
      // Obtener todas las filas de la tabla
      var filas = document.querySelectorAll("#tablaPersonas tbody tr");

// Configuración de paginación
var registrosPorPagina = 10;
var paginaActual = 1;
var totalPaginas = Math.ceil(filas.length / registrosPorPagina);

// Función para mostrar las filas correspondientes a la página actual
function mostrarFilas() {
    var inicio = (paginaActual - 1) * registrosPorPagina;
    var fin = inicio + registrosPorPagina;
    
    filas.forEach(function (fila, indice) {
        if (indice >= inicio && indice < fin) {
            fila.style.display = "table-row";
        } else {
            fila.style.display = "none";
        }
    });
}

// Mostrar la página actual al cargar la página
mostrarFilas();

// Manejadores de eventos para los botones de paginación
document.getElementById("paginaAnterior").addEventListener("click", function() {
    if (paginaActual > 1) {
        paginaActual--;
        mostrarFilas();
    }
});

document.getElementById("paginaSiguiente").addEventListener("click", function() {
    if (paginaActual < totalPaginas) {
        paginaActual++;
        mostrarFilas();
    }
});
</script>
<script>
    // Obtener todos los botones de eliminación
    var btnsEliminar = document.querySelectorAll(".btnEliminar");

    // Agregar evento de clic a cada botón de eliminación
    btnsEliminar.forEach(function (btnEliminar) {
        btnEliminar.addEventListener("click", function () {
            var idMedicacion = this.getAttribute("data-id");
            document.getElementById("id_nom_med_eliminar").value = idMedicacion;
        });
    });
</script>
<!-- Script para obtener todos los botones de edición -->
<script>
    // Obtener todos los botones de edición
    var btnsEditar = document.querySelectorAll(".btnEditar");

    // Agregar evento de clic a cada botón de edición
    btnsEditar.forEach(function (btnEditar) {
        btnEditar.addEventListener("click", function () {
            var idMedicacion = this.getAttribute("data-id");
            var nombreMedicacion = this.getAttribute("data-nombre");
            var marcaMedicacion = this.getAttribute("data-marca");
            var cajaCompMedicacion = this.getAttribute("data-caja");

            // Asignar los valores a los campos del formulario en el modal
            document.getElementById("editIdNomMed").value = idMedicacion;
            document.getElementById("editNomMed").value = nombreMedicacion;
            document.getElementById("editMarcaMed").value = marcaMedicacion;
            document.getElementById("editCajaComp").value = cajaCompMedicacion;
        });
    });
</script>
      
    
    

<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   