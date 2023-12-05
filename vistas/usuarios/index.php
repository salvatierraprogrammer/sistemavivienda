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
<h1>Registro de usuarios</h1>
<?php if ($mensaje !== "") { ?>
    <div class="alert <?php echo (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
        <?php echo $mensaje; ?>
        <!-- Botón de cierre (cruz) -->
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>
    <div class="row">
        <div class="col-lg-12">
           <!-- Botón para abrir el modal -->
           <div class="modal-footer">
           <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">
           <i class="fas fa-plus"></i> Agregar usuario</button>
           </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="tablaPersonas" class="table table-striped table-bordered table-condensed" style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Nombre y Apellido</th>
                                     
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
                        $consulta = "SELECT id_usuarios, nombre_u, apellido_u FROM usuarios";
                        $resultado = $conn->query($consulta);

                        // Verificar si la consulta se ejecutó correctamente
                        if ($resultado) {
                            // Obtener los datos de la consulta en un arreglo asociativo
                            $data = $resultado->fetch_all(MYSQLI_ASSOC);

                            // Verificar si hay registros en el arreglo
                            if (count($data) > 0) {
                                foreach ($data as $dat) {
                                    // Obtener los datos de cada registro
                                    $id_usuarios = isset($dat['id_usuarios']) ? $dat['id_usuarios'] : 'N/A';
                                    $nombre_u = isset($dat['nombre_u']) ? $dat['nombre_u'] : 'N/A';
                                    $apellido_u = isset($dat['apellido_u']) ? $dat['apellido_u'] : 'N/A';
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $id_usuarios ?></td>
                                        <td class="text-center"><?php echo $nombre_u ?> <?php echo $apellido_u ?></td>
                                       
                                        
                                        <td class="text-center">
                                        <div class='btn-group'>
                                        <button type="button" class="btn btn-warning btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $id_usuarios ?>" data-nombre="<?php echo $nombre_u ?>" data-apellido="<?php echo $apellido_u ?>">Editar</button>               
                                        <button type="button" class="btn btn-danger btnEliminar" data-toggle="modal" data-target="#modalConfirmacion" data-id="<?php echo $id_usuarios ?>">Eliminar</button>                </td>
                                        </div>
                                    </tr>
                                    <?php
                                }
                            } else {
                                // Mostrar un mensaje si no hay registros
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
                        <button id="paginaAnterior" class="btn btn-primary mr-2">Anterior</button>
                        <button id="paginaSiguiente" class="btn btn-primary">Siguiente</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Agregar nueva Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonas" action="controller/procesar_usuarios.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_u" class="col-form-label">Nombre:</label>
                        <input type="text" name="nombre_u" class="form-control" id="nombre_u">
                    </div>
                    <div class="form-group">
                        <label for="apellido_u" class="col-form-label">Apellido:</label>
                        <input type="text" name="apellido_u" class="form-control" id="apellido_u">
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
                <form id="editarForm" action="controller/editar_usuarios.php" method="POST">
                    <!-- Campo oculto para el id_usuarios -->
                    <input type="hidden" name="id_usuarios" id="editarIdUsuarios" value="<?php echo $id_usuarios  ?>">
                    <div class="form-group">
                        <label for="editarNombreUsuario">Nombre:</label>
                        <input type="text" name="nombre_u" class="form-control" id="editarNombreUsuario">
                    </div>
                    <div class="form-group">
                        <label for="editarApellidoUsuario">Apellido:</label>
                        <input type="text" name="apellido_u" class="form-control" id="editarApellidoUsuario">
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

<!-- Modal de confirmación -->
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
                ¿Estás seguro de que deseas eliminar el Usuario?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="controller/eliminar_usuarios.php" method="POST">
                    <!-- Campo oculto para el id_casas -->
                    <input type="hidden" name="id_usuarios" id="id_usuarios">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEliminar" class="btn btn-danger">Eliminar</button>
                </form>
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
    var btnEliminarArray = document.querySelectorAll(".btnEliminar");
    var idUsuariosInput = document.getElementById("id_usuarios");

    btnEliminarArray.forEach(function (btnEliminar) {
        btnEliminar.addEventListener("click", function () {
            var idUsuarios = this.getAttribute("data-id");
            idUsuariosInput.value = idUsuarios;
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
            var idUsuarios = this.getAttribute("data-id");
            var nombreUsuario = this.getAttribute("data-nombre");
            var apellidoUsuario = this.getAttribute("data-apellido");

            // Asignar los valores a los campos del formulario en el modal
            document.getElementById("editarIdUsuarios").value = idUsuarios;
            document.getElementById("editarNombreUsuario").value = nombreUsuario;
            document.getElementById("editarApellidoUsuario").value = apellidoUsuario;
        });
    });
</script>
      
    
    

<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   