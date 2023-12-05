<?php require_once "vistas/parte_superior.php"?>
<?php 
 // Agregamos esto para verificar el valor de $operadores
 if (isset($_POST['id_usuarios']) && isset($_POST['id_operadores']) && isset($_POST['id_casas'])) { // Cambiado a $_GET si se espera obtenerlo de la URL
    $id_usuarios = $_POST['id_usuarios'];
    $id_operadores = $_POST['id_operadores'];
    $id_casas = $_POST['id_casas']; // Cambiado a $_GET si se espera obtenerlo de la URL
    // Resto de tu código aquí
} else 
    // Manejo de error o redirección si $_GET['id_usuarios'] no está definido
if (isset($_GET['id_usuarios']) && isset($_GET['id_operadores']) && isset($_GET['id_casas'])) {
        $id_usuarios = $_GET['id_usuarios'];
        $id_operadores = $_GET['id_operadores'];
        $id_casas = $_GET['id_casas'];
    } else {
        echo "ID de usuarios no definido.";
        exit;
}
// Aquí debes realizar la consulta a la base de datos para obtener los detalles de los operadores según los IDs que recibiste en $operadores.
include_once 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname); 
// Por ejemplo:
// Convierte la cadena $operadores en un arreglo separando los IDs por comas
$operadores_ids = explode(',', $id_operadores);

// Consulta para obtener los detalles de los operadores
$consulta_operadores = "SELECT * FROM operadores WHERE id_operadores IN (" . implode(',', $operadores_ids) . ")";
$resultado_operadores = $conn->query($consulta_operadores);

if ($resultado_operadores) {
    $data_operadores = $resultado_operadores->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error en la consulta de operadores: " . $conn->error);
   
}
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
<div class="container">
    <h1><i class="fas fa-fw fa-user"></i> Datos de operadores</h1>
    <div class="row">
        <div class="col-lg-12">
           <!-- Botón para abrir el modal -->
            <!-- <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">Nuevo</button> -->
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <?php
        foreach ($data_operadores as $operador) {
            // Obtener los datos de cada operador
            $id_operadores = isset($operador['id_operadores']) ? $operador['id_operadores'] : 'N/A';
            $nombre_operador = isset($operador['nombre_o']) ? $operador['nombre_o'] : 'N/A';
            $apellido_operador = isset($operador['apellido_o']) ? $operador['apellido_o'] : 'N/A';
            ?>
            <div class="col-lg-4 mb-4">
                <div class="card text-center" style="background: aliceblue;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title"><i class="fas fa-fw fa-user"></i> <?php echo $nombre_operador . ' ' . $apellido_operador ?></h5>
                        <a href="asistencia.php?id_casas=<?php echo $id_casas ?>&id_operadores=<?php echo $id_operadores ?>&id_usuarios=<?php echo $id_usuarios;?>" class="btn btn-primary mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                            </svg> Asistencia
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<!-- Modal para CRUD -->
<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Operador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonas">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="usuarios" class="col-form-label">Usuario:</label>
                        <input type="text" class="form-control" id="usuarios" value="">
                    </div>
                    <div class="form-group">
                        <label for="operadores" class="col-form-label">Operador:</label>
                        <input type="text" class="form-control" id="operadores">
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
      
    

<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   