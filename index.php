<?php
require_once "vistas/parte_superior.php";

// Verificar el rol del usuario y redirigir si es necesario
if ($userRole == 1) {
    header('Location: panel_operador.php');
    exit;
}

$mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable
// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
?>
<!-- Inicio contenido principal -->
<style>
    .card, .card-flex {
        border: 1px solid #ccc;
        border-radius: 25px; /* Todos los bordes con radio de 25px */
        padding: 10px;
        background-color: aliceblue;
        color: #000;
        fill: var(--e-global-color-primary);
        color: var(--e-global-color-primary);
        border-style: solid;
        border-width: 2px;
        border-color: var(--e-global-color-primary);
        box-shadow: 6px 6px 0px -1px #000000;
    }
    .btn {
        /* display: block;
        width: 90%;
        padding: 10px;
        font-size: 20px;
        font-family: initial;
        text-align: center;
        background-color: aliceblue;
        color: #000;
        border: 1px solid #000;
        text-decoration: none;
        border-radius: 25px;
        border-style: solid;
        border-width: 2px;
        border-color: var(--e-global-color-primary);
        box-shadow: 6px 6px 0px -1px #000000;
        transition: transform 0.3s;  */
    }
</style>    

<div class="container">
    <h1>Administrador Viviendas</h1>
    <!-- Mostrar mensajes de Bootstrap Alert en el HTML -->
    <div class="container">
        <?php if ($mensaje !== "") { ?>
            <div class="alert <?php echo (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo $mensaje; ?>
                <!-- Botón de cierre (cruz) -->
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
        <div class="modal-footer">
           <!-- Botón para abrir el modal -->
          <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">
          <i class="fas fa-plus"></i> Agregar vivienda</button>
        </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
       
        <?php
            // Incluir el archivo de conexión
            include_once 'bd/conexion.php';
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar si la conexión se estableció correctamente
            if ($conn->connect_error) {
                die("Error al conectar a la base de datos: " . $conn->connect_error);
            }

            // Realizar la consulta a la base de datos
            $consulta = "SELECT id_casas, nombre_c, direccion_c, telefono_c FROM casas";
            $resultado = $conn->query($consulta);

            // Verificar si la consulta se ejecutó correctamente
            if ($resultado) {
                // Obtener los datos de la consulta en un arreglo asociativo
                $data = $resultado->fetch_all(MYSQLI_ASSOC);

                // Verificar si hay registros en el arreglo
                if (count($data) > 0) {
                    foreach ($data as $dat) {
                        // Obtener los datos de cada registro
                        $id_casas = isset($dat['id_casas']) ? $dat['id_casas'] : 'N/A';
                        $nombre_c = isset($dat['nombre_c']) ? $dat['nombre_c'] : 'N/A';
                        $direccion_c = isset($dat['direccion_c']) ? $dat['direccion_c'] : 'N/A';
                        $telefono_c = isset($dat['telefono_c']) ? $dat['telefono_c'] : 'N/A';
        ?>
 
             <div class="col-lg-6">
                <div class="card mb-4 custom-card" style="background: aliceblue;">
                    <div class="card-body">
                        <input type="hidden" value="<?php echo $id_casas ?> ">
                        <h5 class="card-title"><i class="fas fa-home"></i> Vivienda: <?php echo $nombre_c ?> <button type="button" class="btn btn-warning btnEditar shadow" data-toggle="modal"
                                data-target="#modalEditar" data-id="<?php echo $id_casas ?>"
                                data-nombre="<?php echo $nombre_c ?>" data-direccion="<?php echo $direccion_c ?>"
                                data-telefono="<?php echo $telefono_c ?>">
                                <i class="fas fa-edit"></i> 
                            </button>
                            <button type="button" class="btn btn-danger btnEliminar shadow" data-toggle="modal"
                                data-target="#modalConfirmacion" data-id="<?php echo $id_casas ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </h5>
                        <p class="card-text"><i class="fas fa-map-marker-alt"></i> Dirección: <?php echo $direccion_c ?></p>
                        <p class="card-text"><i class="fas fa-phone"></i> Teléfono: <?php echo $telefono_c ?></p>
                        <div class="text-center">
                        <div class="btn-group" role="group">
                            <a href="buttons.php?id=<?php echo $id_casas ?>" class="btn btn-info btnVista shadow">
                            <i class="fa-solid fa-right-to-bracket"></i> Ingresar
                            </a>
                            
                           
                        </div>
                        </div>
                    </div>
                </div>
            </div>
                <?php
                    }
                } else {
                    // Mostrar un mensaje si no hay registros
                    ?>
                    <p>No se encontraron viviendas.</p>
                    <?php
                }
            } else {
                die("Error en la consulta: " . $conn->error);
            }
            ?>
        </div>
    </div>   
 </div>
   
<!-- Modal para CRUD -->
<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nueva Casa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonas" action="controller/procesar_casas.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_c" class="col-form-label">Nombre de casa:</label>
                        <input type="text" name="nombre_c" class="form-control" id="nombre_c">
                    </div>
                    <div class="form-group">
                        <label for="direccion_c" class="col-form-label">Dirección:</label>
                        <input type="text" name="direccion_c" class="form-control" id="direccio_c">
                    </div>
                    <div class="form-group">
                        <label for="telefono_c" class="col-form-label">Teléfono:</label>
                        <input type="text" name="telefono_c" class="form-control" id="telefono_c">
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
<!-- Modal de confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmacionLabel"><i class="fas fa-trash"></i> Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta casa?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="controller/eliminar_casas.php" method="POST">
                    <!-- Campo oculto para el id_casas -->
                    <input type="hidden" name="id_casas" id="id_casas">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEliminar" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Casa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar la casa -->
                <form id="editarForm" action="controller/editar_casas.php" method="POST">
                    <!-- Campo oculto para el id_casas -->
                    <input type="hidden" name="id_casas" id="editarIdCasas">
                    <div class="form-group">
                        <label for="editarNombreCasa">Nombre de casa:</label>
                        <input type="text" name="nombre_c" class="form-control" id="editarNombreCasa" value="<?php echo $nombre_c ?>">
                    </div>
                    <div class="form-group">
                        <label for="editarDireccionCasa">Dirección:</label>
                        <input type="text" name="direccion_c" class="form-control" id="editarDireccionCasa">
                    </div>
                    <div class="form-group">
                        <label for="editarTelefonoCasa">Teléfono:</label>
                        <input type="text" name="telefono_c" class="form-control" id="editarTelefonoCasa">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="editarForm" class="btn btn-warning"><i class="fas fa-edit"></i> Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    var btnEliminarArray = document.querySelectorAll(".btnEliminar");
    var idCasasInput = document.getElementById("id_casas");

    btnEliminarArray.forEach(function (btnEliminar) {
        btnEliminar.addEventListener("click", function () {
            var idCasas = this.getAttribute("data-id");
            idCasasInput.value = idCasas;
        });
    });
</script>
<script>
    // Obtener todos los botones de edición
    var btnsEditar = document.querySelectorAll(".btnEditar");

    // Agregar evento de clic a cada botón de edición
    btnsEditar.forEach(function (btnEditar) {
        btnEditar.addEventListener("click", function () {
            var idCasas = this.getAttribute("data-id");
            document.getElementById("editarIdCasas").value = idCasas;

            // Obtener los valores de nombre, direccion y telefono de la fila correspondiente
            var nombreCasa = this.getAttribute("data-nombre");
            var direccionCasa = this.getAttribute("data-direccion");
            var telefonoCasa = this.getAttribute("data-telefono");

            // Mostrar los valores en los campos del formulario de edición
            document.getElementById("editarNombreCasa").value = nombreCasa;
            document.getElementById("editarDireccionCasa").value = direccionCasa;
            document.getElementById("editarTelefonoCasa").value = telefonoCasa;
        });
    });
</script>
<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>
