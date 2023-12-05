<?php
require_once "vistas/parte_superior.php"?>
<!-- Inicio contenido principal -->

    

<div class="container">
<h1>Administrador</h1>
    <div class="row">
        <div class="col-lg-12">
           <!-- Botón para abrir el modal -->
<button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">Nuevo</button>

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
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Telefono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Incluir el archivo de conexión
                    include_once '../../bd/conexion.php';
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
                                <tr>
                                    <td><?php echo $id_casas ?></td>
                                    <td><?php echo $nombre_c ?></td>
                                    <td><?php echo $direccion_c ?></td>
                                    <td><?php echo $telefono_c ?></td>
                                    <td>
                                    <a href="buttons.php?id=<?php echo $id_casas ?>" class="btn btn-info btnVista">Vista</a>
                                <button type="button" class="btn btn-danger btnEliminar">Eliminar</button>
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
            </div>
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
            <form id="formPersonas">
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
      
    
    
</div>
<!--FIN del cont principal-->
<script>
    $(document).ready(function () {
        // Al hacer clic en el botón "Guardar", se enviará el formulario
        $("#btnGuardar").click(function (event) {
            event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

            // Obtener los valores del formulario
            var nombreCasa = $("#nombre_c").val();
            var direccionCasa = $("#direccion_c").val();
            var telefonoCasa = $("#telefono_c").val();

            // Crear un objeto con los datos que se enviarán al servidor
            var datosCasa = {
                nombre: nombreCasa,
                direccion: direccionCasa,
                telefono: telefonoCasa
            };

            // Enviar la solicitud AJAX para guardar los datos
            $.ajax({
                type: "POST", // Puedes cambiar el tipo de solicitud según tu servidor
                url: "URL_DEL_ENDPOINT_PARA_GUARDAR_CASA", // Reemplaza esto con la URL de tu servidor
                data: datosCasa,
                success: function (response) {
                    // Manejar la respuesta del servidor si es necesario
                    console.log("Casa guardada exitosamente");
                    // Aquí puedes realizar alguna acción adicional, como cerrar el modal o recargar la página.
                },
                error: function (error) {
                    // Manejar el error en caso de que falle la solicitud
                    console.error("Error al guardar la Casa:", error);
                }
            });
        });
    });
</script>
<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   