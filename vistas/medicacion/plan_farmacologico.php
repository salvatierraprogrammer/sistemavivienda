<?php require_once "vistas/parte_superior.php"?>
<?php 
// Aquí debes realizar la consulta a la base de datos para obtener los detalles de los operadores según los IDs que recibiste en $operadores.
include_once 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname); 
?>

<div class="container">
    <h1><i class="fas fa-fw fa-users"></i> Registro de usuarios</h1>
    <div class="row">
        <div class="col-lg-12">
           <!-- Botón para abrir el modal -->
            <!-- <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCRUD">Nuevo</button> -->
        </div>
    </div>
</div>
<br>
<?php
include_once 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id_usuarios, nombre_u, apellido_u FROM usuarios";
$result = $conn->query($sql);
?>

<div class="container">
    <div class="row">
        <?php
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-fw fa-user"></i> <?php echo $row['nombre_u']; ?> <?php echo $row['apellido_u']; ?></h5>
                        <p class="card-text"></p>
                        <div class="text-center">
                        <form action="plan_farmacologico.php" method="POST">
                        <input type="hidden" name="id_usuarios" value="<?php echo $row['id_usuarios']; ?>">
                        <div class="btn-group">
                        <button type="sumit" class="btn btn-info">
                         <i class="fas fa-pills"> </i>
                        Plan Farmacologico
                        </button>
                        <a class="btn btn-warning" href="historial_med.php?id_usuarios=<?php echo $row['id_usuarios']; ?>">
                            <i class="fas fa-history"></i> Historial
                        </a>
                        </div>              
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        $conn->close();
        ?>
    </div>
</div>      
<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   