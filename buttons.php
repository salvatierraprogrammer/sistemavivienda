<?php
// Incluir el archivo de conexión
include_once 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Verificar si se ha recibido el parámetro "id" a través de GET
if (isset($_GET['id'])) {
    // Obtener el valor de id_casas desde la URL
    $id_casas = $_GET['id'];

    // Consulta para obtener la vivienda con el id_casas proporcionado
    $consulta = "SELECT usuarios.id_usuarios, usuarios.nombre_u, usuarios.apellido_u, COUNT(viviendas.id_operadores) AS cantidad_operadores, GROUP_CONCAT(viviendas.id_operadores) AS operadores 
                 FROM viviendas 
                 JOIN usuarios ON viviendas.id_usuarios = usuarios.id_usuarios 
                 WHERE viviendas.id_casas = $id_casas
                 GROUP BY usuarios.nombre_u, usuarios.apellido_u";

} else {
    // Consulta para obtener todas las viviendas con el nombre de usuario y la cantidad de operadores
    $consulta = "SELECT usuarios.id_usuarios, usuarios.nombre_u, usuarios.apellido_u, COUNT(viviendas.id_operadores) AS cantidad_operadores, GROUP_CONCAT(viviendas.id_operadores) AS operadores 
                 FROM viviendas 
                 JOIN usuarios ON viviendas.id_usuarios = usuarios.id_usuarios 
                 GROUP BY usuarios.nombre_u, usuarios.apellido_u";
}

$resultado = $conn->query($consulta);

// Verificar si la consulta se ejecutó correctamente
if ($resultado) {
    // Obtener los datos de la consulta en un arreglo asociativo
    $data = $resultado->fetch_all(MYSQLI_ASSOC);

    // Obtener el nombre de la vivienda si se proporcionó el parámetro "id"
    $nombre_vivienda = isset($nombre_vivienda) ? $nombre_vivienda : 'N/A';
    // Obtener el nombre de la vivienda si se proporcionó el parámetro "id"
$nombre_vivienda = 'Todas las viviendas'; // Valor predeterminado
if (isset($_GET['id'])) {
    // Consulta para obtener el nombre de la vivienda con el id_casas proporcionado
    $consulta_vivienda = "SELECT nombre_c FROM casas WHERE id_casas = $id_casas";
    $resultado_vivienda = $conn->query($consulta_vivienda);

    if ($resultado_vivienda && $resultado_vivienda->num_rows > 0) {
        $vivienda = $resultado_vivienda->fetch_assoc();
        $nombre_vivienda = $vivienda['nombre_c'];
    }
}
    require_once "vistas/parte_superior.php";
    ?>
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
</style>  
    <!-- Inicio contenido principal -->
    <div class="container">
        <h1>
            <?php echo "<i class='fas fa-home'></i>  Vivienda: " . $nombre_vivienda; ?></h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="modal-footer">
                <!-- Botón para abrir el modal -->
                <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">
                    <i class="fas fa-fw fa-plus"></i> Agregar Usuarios</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php
    if (count($data) > 0) {
    ?>
    <div class="container">
        <div class="row">
            <?php
            foreach ($data as $dat) {
                // Obtener los datos de cada registro
                $id_usuarios = isset($dat['id_usuarios']) ? $dat['id_usuarios'] : 'N/A';
                $nombre_usuario = isset($dat['nombre_u']) ? $dat['nombre_u'] : 'N/A';
                $apellido_usuario = isset($dat['apellido_u']) ? $dat['apellido_u'] : 'N/A';
                $cantidad_operadores = isset($dat['cantidad_operadores']) ? $dat['cantidad_operadores'] : 0;
                $operadores = isset($dat['operadores']) ? $dat['operadores'] : 'N/A';
                ?>
                <div class="col-lg-4 mb-4">
                    <div class="card" style="border-radius: 1.35rem; background: aliceblue;">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-building-user"></i> <?php echo $nombre_usuario . ' ' . $apellido_usuario ?></h5>
                            <p class="card-text"><i class="fas fa-fw fa-user"></i> Operadores: <span class="badge badge-secondary"><?php echo $cantidad_operadores ?></span> <a href="operadores.php?id_casas=<?php echo $id_casas ?>&id_operadores=<?php echo $operadores ?>&id_usuarios=<?php echo $id_usuarios?>" class="btn btn-primary">
                                <i class="fa-solid fa-users-viewfinder"></i> Ver
                                </a></p>
                            <hr>
                            <div class="btn-group" role="group">
                                
                                <button type="button" class="btn btn-info btnAgregar" data-toggle="modal" data-target="#modalAgregarOperador" data-nombre-usuario="<?php echo $nombre_usuario . ' ' . $apellido_usuario ?>" data-id-usuario="<?php echo $id_usuarios ?>">
                                <i class="fa-solid fa-user-plus"></i> Agregar operador
                                </button>
                                <!-- <button type="button" class="btn btn-danger btnEliminar" data-toggle="modal" data-target="#modalEliminar" data-id-usuario="<?php echo $id_usuarios ?>">
                                <i class="fa-solid fa-trash"></i>
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <!--FIN del contenido principal-->
    <?php
    } else {
    ?>
    <div class="container">
        <div class="alert alert-warning" role="alert">
            No se encontraron usuarios para esta vivienda.
        </div>
       
    </div>
    <?php
    }
    ?>

    <!-- Fin contenido -->
    <?php
    require_once "vistas/parte_inferior.php";
} else {
    die("Error en la consulta: " . $conn->error);
}
?>
<!-- Modal para agregar usuario -->
<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar usuario a la vivienda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonas" action="controller/agregar_usuario_v.php" method="POST">
                <div class="modal-body">
                <div class="form-group">
                        <label for="nombre_v" class="col-form-label">Vivienda:</label>
                        <input type="hidden" name="id_casas" value="<?php echo $id_casas ?>" >
                        <input type="text" class="form-control" id="nombre_v" value="<?php echo $nombre_vivienda; ?>" readonly>
                    </div>
                <!-- Realizar la consulta para obtener los nombres e ID de usuarios -->
                      
                <?php
                     include 'bd/conexion.php';
                     $conn = new mysqli($servername, $username, $password, $dbname);
                     
                     // Verificar si la conexión se estableció correctamente
                     if ($conn->connect_error) {
                         die("Error al conectar a la base de datos: " . $conn->connect_error);
                     }
                     
                     // Realizar la consulta para obtener los usuarios no asignados a viviendas
                    $consultaUsuarios = "SELECT id_usuarios, nombre_u, apellido_u FROM usuarios 
                                        WHERE id_usuarios NOT IN (SELECT DISTINCT id_usuarios FROM viviendas)";
                    $resultadoUsuarios = $conn->query($consultaUsuarios);

                    // Verificar si la consulta se ejecutó correctamente
                    if ($resultadoUsuarios && $resultadoUsuarios->num_rows > 0) {
                        ?>
                        <div class="form-group">
                            <label for="nombre_u" class="col-form-label">Usuario:</label>
                            <select class="form-control" id="nombre_u" name="id_usuarios">
                                <!-- Iterar sobre los resultados y crear las opciones para el campo de selección -->
                                <?php
                                while ($row = $resultadoUsuarios->fetch_assoc()) {
                                    $id_usuario = $row['id_usuarios'];
                                    $nombre_usuario = $row['nombre_u'];
                                    $apellido_usuario = $row['apellido_u'];
                                    ?>
                                    <option value="<?php echo $id_usuario; ?>"><?php echo $nombre_usuario . ' ' . $apellido_usuario; ?></option>
                                    <?php
                                }
            ?>
                             </select>
                         </div>
                         <?php
                     } else {
                         // Mostrar un mensaje si no hay usuarios sin asignar a viviendas
                         ?>
                         <div class="alert alert-warning" role="alert">
                             No hay usuarios sin asignar a viviendas.
                         </div>
                         <?php
                     }
                     
                     $conn->close();
                     ?>      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
      
 <!-- Agregar modal para agregar operador a usuarios -->
<div class="modal fade" id="modalAgregarOperador" tabindex="-1" role="dialog" aria-labelledby="modalAgregarOperadorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarOperadorLabel">Agregar Operador a Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarOperador" action="controller/procesar_user_operador.php" method="POST">
                    <div class="form-group">
                        <label for="nombreUsuarios">Nombre del Usuario</label>
                        <input type="text" class="form-control" id="nombreUsuarios" name="usuarios" readonly>
                     
                    </div>
                    <div class="form-group">
                    <label for="operadorSelect">Nombre y Apellido del Operador</label>
                    <select class="form-control" id="operadorSelect" name="id_operadores" required>
                        <option value="" disabled selected>Seleccione un operador</option>
                        <?php
                        // Realizar la consulta para obtener los datos de los operadores
                        include_once 'bd/conexion.php';
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Error al conectar a la base de datos: " . $conn->connect_error);
                        }

                        $consultaOperadores = "SELECT id_operadores, CONCAT(nombre_o, ' ', apellido_o) AS nombre_operador FROM operadores";
                        $resultadoOperadores = $conn->query($consultaOperadores);

                        // Verificar si la consulta se ejecutó correctamente
                        if ($resultadoOperadores && $resultadoOperadores->num_rows > 0) {
                            while ($operador = $resultadoOperadores->fetch_assoc()) {
                                $idOperador = $operador['id_operadores'];
                                $nombreOperador = $operador['nombre_operador'];
                        ?>
                        <option value="<?php echo $idOperador ?>"><?php echo $nombreOperador ?></option>
                        <?php
                            }
                        } else {
                            // Si no hay operadores registrados
                            echo '<option value="" disabled>No hay operadores registrados</option>';
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                        <!-- Agregar más campos para los datos del operador si es necesario -->
                    <input type="hidden" name="id_usuarios" value="<?php echo $id_usuarios ?>">
                    <input type="hidden" name="id_casas" value="<?php echo $id_casas?>">
                    <input type="hidden" name="id_coperadores" value="<?php echo $id_operadores?>">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Agregar operador</button>
                </form>
            </div>
        </div>
    </div>
</div>   
<!-- Modal para confirmar la eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este usuario?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="controller/eliminar_user_operador.php" method="POST">
                <input type="hidden" name="id_usuarios" value="<?php echo $id_usuarios?>">
                <button type="submit" class="btn btn-danger" id="btnEliminarUsuario">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>    
</div>
<!--FIN del cont principal-->
<script>
    $(document).ready(function() {
        // Captura el clic en el botón "Agregar"
        $('.btnAgregar').on('click', function() {
            // Obtén el nombre y el id del usuario desde los atributos data
            var nombreUsuario = $(this).data('nombre-usuario');
            var idUsuario = $(this).data('id-usuario');

            // Muestra el nombre del usuario en el campo de texto del modal
            $('#nombreUsuarios').val(nombreUsuario);
            $('input[name="id_usuarios"]').val(idUsuario);
        });
    });
    $(document).ready(function(){
        $('.btnEliminar').on('click', function() {
            var idUsuarios = $(this).data('id-usuario');
            
            $('input[name="id_usuarios"]').val(idUsuarios);
        
        });

        
    });
</script>
<!-- Fincontenido -->


   