<?php require_once "vistas/parte_superior.php"?>
<!-- Inicio contenido principal -->
<?php
$mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable

// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
?>
    

<div class="container">
<h1>Registro de administradores</h1>
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
                            <!-- <th class="text-center"></th> -->
                            <th class="text-center">Telefono</th>
                            <th class="text-center">Estado</th>
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
            $consulta = "SELECT o.id_operadores, o.nombre_o, o.apellido_o, o.email_o, o.telefono_o, o.ingreso_o, u.rol
            FROM operadores o
            INNER JOIN users u ON o.id_operadores = u.id_operadores
            WHERE u.rol IN (0, 2)
            ORDER BY o.id_operadores DESC";

            $resultado = $conn->query($consulta);

                // Verificar si la consulta se ejecutó correctamente
                if ($resultado) {
                    // Obtener los datos de la consulta en un arreglo asociativo
                    $data = $resultado->fetch_all(MYSQLI_ASSOC);

                    // Verificar si hay registros en el arreglo
                    if (count($data) > 0) {
                        foreach ($data as $dat) {
                            // Obtener los datos de cada registro
                            $id_operadores = isset($dat['id_operadores']) ? $dat['id_operadores'] : 'N/A';
                            $nombre_o = isset($dat['nombre_o']) ? $dat['nombre_o'] : 'N/A';
                            $apellido_o = isset($dat['apellido_o']) ? $dat['apellido_o'] : 'N/A';
                            $email_o = isset($dat['email_o']) ? $dat['email_o'] : 'N/A';
                            $telefono_o = isset($dat['telefono_o']) ? $dat['telefono_o'] : 'N/A';
                            $rol = isset($dat['rol']) ? $dat['rol'] : 'N/A';
                            
                            ?>
            <tr>
                <td class="text-center"><?php echo $id_operadores ?></td>
                <td class="text-center"><?php echo $nombre_o ?> <?php echo $apellido_o ?></td>
                <!-- <td class="text-center"></td> -->
                <td class="text-center"><?php echo $telefono_o ?></td>
                <td class="text-center">
                    <?php
                    if ($rol == 0) {
                        echo '<span class="badge bg-danger text-white">Desactivado</span>';
                    } elseif ($rol == 1) {
                        echo '<span class="badge bg-success text-white">Activado</span>';
                    } elseif ($rol == 2) {
                        echo '<span class="badge bg-primary text-white">Administrador</span>';
                    } else {
                        echo 'Invalid Rol'; // You can customize this message for other cases
                    }
                    ?>
                </td>
                <td class="text-center">
                <div class='btn-group'>
                <?php
if ($rol == 0) {
    echo '<button type="button" class="btn btn-success btnAlta" data-toggle="modal" data-target="#modalAlta" data-id="'.$id_operadores.'" data-nombre="'.$nombre_o.'" data-apellido="'.$apellido_o.'" data-email="'.$email_o.'">Activar</button>';
} elseif ($rol > 0) {
    echo '<button type="button" class="btn btn-primary btnAlta" data-toggle="modal" data-target="#modalAlta" data-id="'.$id_operadores.'" data-nombre="'.$nombre_o.'" data-apellido="'.$apellido_o.'" data-email="'.$email_o.'" >Desactivar</button>';
} else {
    echo 'Invalid Rol'; // You can customize this message for other cases
}
?>
                <button type="button" class="btn btn-warning btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $id_operadores ?>" data-nombre="<?php echo $nombre_o ?>" data-apellido="<?php echo $apellido_o ?>" data-email="<?php echo $email_o ?>" data-telefono="<?php echo $telefono_o ?>">Editar</button>
                <button type="button" class="btn btn-danger btnEliminar" data-toggle="modal" data-target="#modalConfirmacion" data-id="<?php echo $id_operadores ?>">Eliminar</button>                
                </div>
            </td>
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
                <!-- Botones de paginación -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button id="paginaAnterior" class="btn btn-info mr-2"> < </button>
                        <button id="paginaSiguiente" class="btn btn-info"> > </button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<!-- Modal para agregar CRUD -->
<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nueva Operador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonas" action="controller/procesar_operadores.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_o" class="col-form-label">Nombre de operador:</label>
                        <input type="text" name="nombre_o" class="form-control" id="nombre_o">
                    </div>
                    <div class="form-group">
                        <label for="apellido_o" class="col-form-label">Apeliido:</label>
                        <input type="text" name="apellido_o" class="form-control" id="apellido_o">
                    </div>
                    <div class="form-group">
                        <label for="email_o" class="col-form-label">Email:</label>
                        <input type="text" name="email_o" class="form-control" id="email_o">
                    </div>
                    <div class="form-group">
                        <label for="telefono_o" class="col-form-label">Telefono:</label>
                        <input type="text" name="telefono_o" class="form-control" id="telefono_o">
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
                <h5 class="modal-title" id="modalEditarLabel">Editar Operador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar el operador -->
                <form id="editarForm" action="controller/editar_operadores.php" method="POST">
                    <!-- Campo oculto para el id_operadores -->
                    <input type="hidden" name="id_operadores" id="editarIdOperadores" value="<?php echo $id_operadores ?>">
                    <div class="form-group">
                        <label for="editarNombreOperador">Nombre:</label>
                        <input type="text" name="nombre_o" class="form-control" id="editarNombreOperador">
                    </div>
                    <div class="form-group">
                        <label for="editarApellidoOperador">Apellido:</label>
                        <input type="text" name="apellido_o" class="form-control" id="editarApellidoOperador">
                    </div>
                    <div class="form-group">
                        <label for="editarEmailOperador">Email:</label>
                        <input type="text" name="email_o" class="form-control" id="editarEmailOperador">
                    </div>
                    <div class="form-group">
                        <label for="editarTelefonoOperador">Teléfono:</label>
                        <input type="text" name="telefono_o" class="form-control" id="editarTelefonoOperador">
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

<!-- Modal de eliminar -->
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
                ¿Estás seguro de que deseas eliminar el Operador?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="controller/eliminar_operadores.php" method="POST">
                    <!-- Campo oculto para el id_casas -->
                    <input type="hidden" name="id_operadores" id="id_operadores">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEliminar" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal para generar usuario y contraseña -->

<div class="modal fade" id="modalAlta" tabindex="-1" role="dialog" aria-labelledby="modalAltaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAltaLabel">Activar cuenta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioAlta" action="controller/procesar_operador_users.php" method="POST">
                    
                <div class="form-group">
                    <input type="hidden" id="id_operadores_input" name="id_operadores" value="<?php echo $id_operadores ?>">
                        <label for="nombreCompleto">Nombre Completo:</label>
                        <input type="text" class="form-control" name="nombreCompleto" id="nombreCompleto" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email"class="form-control" id="email" readonly>
                    </div>
                    <!-- Aquí puedes agregar más campos para el usuario y contraseña -->
                    <div class="form-group">
                    <select id="rol" name="rol" class="form-control">
                        <option value="0">Desactivado</option>
                        <option value="2">Activar administrador</option>
                    </select>
                    </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="guardarUsuario">Guardar cambios</button>
                </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    document.addEventListener("DOMContentLoaded", function() {
        $(".btnAlta").click(function() {
            var id_operadores = $(this).data("id");
            var nombre = $(this).data("nombre");
            var apellido = $(this).data("apellido");
            var email = $(this).data("email");

            $("#id_operadores_input").val(id_operadores); // Cambia el ID aquí
            $("#nombreCompleto").val(nombre + " " + apellido);
            $("#email").val(email);
        });

        $("#guardarUsuario").click(function() {
            var id_operadores = $("#id_operadores_input").val(); // Cambia el ID aquí
            var usuario = $("#usuario").val();
            var password_users = $("#password_users").val();

            // Aquí puedes hacer una llamada AJAX para guardar el usuario y contraseña en la base de datos
            // Por ejemplo, utilizando jQuery.ajax() o fetch API de JavaScript
            // Después de guardar, puedes cerrar el modal usando: $("#modalAlta").modal("hide");
        });
    });
</script>


<script>
    var btnEliminarArray = document.querySelectorAll(".btnEliminar");
    var idOperadoresInput = document.getElementById("id_operadores");

    btnEliminarArray.forEach(function (btnEliminar) {
        btnEliminar.addEventListener("click", function () {
            var idOperadores = this.getAttribute("data-id");
            idOperadoresInput.value = idOperadores;
        });
    });
</script>

<script>
    // Obtener todos los botones de edición
    var btnsEditar = document.querySelectorAll(".btnEditar");

    // Agregar evento de clic a cada botón de edición
    btnsEditar.forEach(function (btnEditar) {
        btnEditar.addEventListener("click", function () {
            var idOperadores = this.getAttribute("data-id");
            var nombreOperador = this.getAttribute("data-nombre");
            var apellidoOperador = this.getAttribute("data-apellido");
            var emailOperador = this.getAttribute("data-email");
            var telefonoOperador = this.getAttribute("data-telefono");

            // Asignar los valores a los campos del formulario en el modal
            document.getElementById("editarIdOperadores").value = idOperadores;
            document.getElementById("editarNombreOperador").value = nombreOperador;
            document.getElementById("editarApellidoOperador").value = apellidoOperador;
            document.getElementById("editarEmailOperador").value = emailOperador;
            document.getElementById("editarTelefonoOperador").value = telefonoOperador;
        });
    });
</script>

<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   