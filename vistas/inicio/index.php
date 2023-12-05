<?php
require_once "vistas/parte_superior.php";

// Inicializar la variable $dioAsistencia en false
$dioAsistencia = false;

// Verifica si la variable $id_operadores está definida en $_GET
if (isset($_GET['id_operadores'])) {
    $id_operadores = intval($_GET['id_operadores']);

    include_once 'bd/conexion.php';

    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Consulta para obtener la última asistencia del operador
    $sql = "SELECT * FROM asistencia WHERE id_operadores = $id_operadores ORDER BY fecha_ingreso DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar si dio asistencia y la hora de retiro es "00:00:00"
        if ($row['asistencia'] === 'Asistio' && $row['hora_retiro'] === '00:00:00') {
            $dioAsistencia = true;
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}

// Redirigir a registro.php si dio asistencia
if ($dioAsistencia) {
    echo "<div class='d-flex justify-content-center align-items-center' style='height: 100vh;'>
    <div class='card' style='width: 18rem;'>
        <div class='card-body'>
            <h5 class='card-title'>¡Continua con la jornada!</h5>
            <p class='card-text'>Recuerda finalizar la jornada laboral.</p>
            <a href='panel_operador.php' class='btn btn-primary'>Ir al Panel de Operador</a>
        </div>
    </div>
</div>";
    exit();
}
?>

<style>
    #panel_a:hover {
        color: rgb(51, 167, 181); /* Cambiar el color del texto o icono */
    }

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
<?php
    include_once 'bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Obtener el ID del operador actual desde la sesión
    $operador_id = $_SESSION['user_id'];

    // Consulta para recuperar el ID del operador y su rol desde la tabla users
    $sql_user = "SELECT id_operadores, username, rol FROM users WHERE id_users = ?";
    // Preparar la consulta
    $stmt_user = $conn->prepare($sql_user);
    // Vincular el parámetro
    $stmt_user->bind_param("i", $operador_id);
    // Ejecutar la consulta
    $stmt_user->execute();
    // Obtener resultados
    $result_user = $stmt_user->get_result();

    // Verificar si se obtuvieron resultados
    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        $id_operadores = $row_user['id_operadores'];
        $username = $row_user['username'];
        $rol = $row_user['rol'];
    } else {
        echo "Operador no encontrado en la tabla users.";
    }

    // Consulta para recuperar las casas
    if ($rol == 1) {
        // Si el rol es 1, mostrar las casas correspondientes al operador
        $sql_casas = "SELECT casas.id_casas, casas.nombre_c
        FROM casas
        INNER JOIN viviendas ON casas.id_casas = viviendas.id_casas
        WHERE viviendas.id_operadores = ?";
    } else {
        // Si el rol es 2, mostrar todas las casas
        $sql_casas = "SELECT id_casas, nombre_c FROM casas";
    }

    // Preparar la consulta
    $stmt_casas = $conn->prepare($sql_casas);

    // Vincular el parámetro si es necesario
    if ($rol == 1) {
        $stmt_casas->bind_param("i", $id_operadores);
    }

    // Ejecutar la consulta
    $stmt_casas->execute();

    // Obtener resultados
    $result_casas = $stmt_casas->get_result();

    // Verificar si se obtuvieron resultados
    if ($result_casas->num_rows > 0) {
        $casas = $result_casas->fetch_all(MYSQLI_ASSOC);
    } else {
        $casas = [];
    }
    // Cerrar la conexión
    $stmt_user->close();
    $stmt_casas->close();
    $conn->close();
    ?>
    <!-- Tu código HTML aquí -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Bienvenido <?php echo $username; ?></h1>
        </div>
        <div class="row">
        <?php foreach ($casas as $casa) { ?>
         <?php $id_casas = $casa['id_casas']; ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-radius: 1.35rem;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Vivienda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $casa['nombre_c']; ?></div>
                        </div>
                        <div class="col-auto">
                            <a class="openModalButton" data-toggle="modal" data-target="#myModal<?php echo $id_casas; ?>" dataCasa="<?php echo $id_casas; ?>" dataOperador="<?php echo $id_operadores; ?>">
                                <i class="fas fa-home fa-2x text-success-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal<?php echo $id_casas; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Comenzar Jornada Laboral</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="registro.php" method="POST">
                <input type="hidden" class="form-control" id="id_operadores" name="id_operadores" value="<?php echo $id_operadores; ?>" readonly>
                <input type="hidden" class="form-control" id="id_casas" name="id_casas" value="<?php echo $id_casas; ?>" readonly>
                <div class="form-group">
                    <label for="selectUsuarios<?php echo $id_casas; ?>">Seleccione un usuario</label>
                    <select class="form-control" id="selectUsuarios<?php echo $id_casas; ?>" name="id_usuarios">
                        <?php
                        // Realiza una consulta para obtener usuarios relacionados con la casa actual sin nombres repetidos
                        include 'bd/conexion.php';
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Verificar si la conexión se estableció correctamente
                        if ($conn->connect_error) {
                            die("Error al conectar a la base de datos: " . $conn->connect_error);
                        }

                        // Realiza una consulta para obtener usuarios relacionados con la casa actual sin nombres repetidos
                        $consultaUsuariosCasa = "SELECT DISTINCT u.id_usuarios, u.nombre_u, u.apellido_u 
                                                FROM usuarios u
                                                INNER JOIN viviendas v ON u.id_usuarios = v.id_usuarios
                                                WHERE v.id_casas = ?";
                        $stmt = $conn->prepare($consultaUsuariosCasa);
                        $stmt->bind_param("i", $id_casas);
                        $stmt->execute();
                        $resultadoUsuarios = $stmt->get_result();

                        // Verificar si la consulta se ejecutó correctamente
                        if ($resultadoUsuarios && $resultadoUsuarios->num_rows > 0) {
                            while ($row = $resultadoUsuarios->fetch_assoc()) {
                                $id_usuarios = $row['id_usuarios'];
                                $nombre_usuario = $row['nombre_u'];
                                $apellido_usuario = $row['apellido_u'];
                                echo "<option value='$id_usuarios' name='id_usuarios'>$nombre_usuario $apellido_usuario</option>";
                            }
                        } else {
                            echo "<option value=''>No se encontraron usuarios para esta casa.</option>";
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Confirmar</button>
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
            </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>
    </div>
    </div>
<?php require_once "vistas/parte_inferior.php" ?>