<?php require_once "vistas/parte_superior.php"; ?>
<!-- Inicio contenido principal -->
<?php
$mensaje = "";

// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}

// Verificar si se proporciona un ID de usuarios, ya sea por POST o GET
if (isset($_POST['id_usuarios']) && isset($_POST['id_casas'])) {
    $id_usuarios = $_POST['id_usuarios'];
    // Resto de tu código aquí
} elseif (isset($_GET['id_usuarios'])) {
    $id_usuarios = $_GET['id_usuarios'];
} else {
    echo "ID de usuarios no definido.";
    exit;
}

include 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

$registrosPorPagina = 5;

// Página actual (obtén el número de página desde la URL)
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcula el desplazamiento (offset) en la consulta
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Modifica la consulta SQL para incluir LIMIT y OFFSET
$query = "SELECT rm.id_registro, u.nombre_u, u.apellido_u, CONCAT(o.nombre_o, ' ', o.apellido_o) AS nombre_operador, rm.fecha_hora, rm.foto
          FROM registro_medicacion rm
          INNER JOIN usuarios u ON rm.id_usuarios = u.id_usuarios
          INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
          WHERE rm.id_usuarios = $id_usuarios
          LIMIT $registrosPorPagina OFFSET $offset";

$resultado = mysqli_query($conn, $query);

$queryTotal = "SELECT COUNT(*) AS totalRegistros FROM registro_medicacion WHERE id_usuarios = $id_usuarios";
$resultadoTotal = mysqli_query($conn, $queryTotal);
$totalRegistros = mysqli_fetch_assoc($resultadoTotal)['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$query_usuario = "SELECT nombre_u, apellido_u FROM usuarios WHERE id_usuarios = $id_usuarios";
$resultado_usuario = mysqli_query($conn, $query_usuario);

if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $nombre_usuario = $fila_usuario['nombre_u'] . ' ' . $fila_usuario['apellido_u'];
} else {
    $nombre_usuario = "Usuario no encontrado";
}
?>
<style>
        /* Estilos para la tabla */
    .table-responsive {
    overflow-x: auto;
    }

    .table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 20px, 20px, 20px, 20px,;
    }

    .table th, .table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ccc;
    }
    .table th, .table td .imagen_med{
        max-width: 100px; 
        max-height: 100px; 
        border: 1px solid; 
        margin-top: 8px;
    }
    .table th, .table td .imagen_med:hover{
        cursor: pointer;
        width: 100%;
        height: 100%;
        border: 2px solid rgb(51,167,181);
    }
    
    
    .table th, .table td p{
        font-size: 15px;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
    }
    tr{
        border: none; /* Eliminar el borde predeterminado */
        height: 2px; /* Establecer la altura de la línea */
        /* background-color: rgb(62, 138, 147); */
        /* color: a; */
    }
    /* Estilo para el icono */
        .fa-user {
             margin-right: 5px;
         }
        .fa-calendar-days{
            margin-right: 5px;
        }
        /* Estilos para el modal */
        .modal-img {
            display: none;
            position: fixed;
            z-index: 9999; /* Asegura que el modal esté en la parte superior */
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
            text-align: center; /* Centra el contenido horizontalmente */
        }

        /* Estilos para el contenido del modal */
        .modal-content {
            max-width: 31%; /* Ajusta el ancho máximo según tus necesidades */
            max-height: 80vh; /* Ajusta la altura máxima según tus necesidades */
            margin: auto; /* Centra horizontalmente y verticalmente */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 10000; /* Asegura que el contenido del modal esté en la parte superior */
        }

        /* Estilos para el botón de cierre del modal */
        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10001; /* Asegura que el botón de cierre esté en la parte superior */
        }

        /* Estilos para los botones dentro del modal */
    .modal-button {
        margin-top: 10px; /* Espacio entre los botones */
        /* Agrega otros estilos según sea necesario */
        }
        /* Estilos específicos para el modal de eliminación */
    .custom-modal .modal-dialog {
        max-width: 100%; /* Ancho personalizado para el modal de eliminación */
        margin: 20px auto; /* Centrado horizontalmente */
    }

    .custom-modal .modal-content {
        background-color: #fff; /* Cambiar el fondo del modal de eliminación */
        border: 2px solid #ccc; /* Cambiar el borde del modal de eliminación */
        border-radius: 10px; /* Cambiar el radio de borde del modal de eliminación */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Cambiar la sombra del modal de eliminación */
    }
    @media (max-width: 768px){
        /* Estilos para la imagen en el modal */
        .modal-content {
            max-width: 95%;
            max-height: 80vh;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    }


    
</style>
    
<div class="container-fluid">
      <!-- Page Heading -->
    <div class="container">
      <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-pills"></i> Registro Medicación <?php echo  $nombre_usuario ?></h1>
        <?php if ($mensaje !== "") { ?>
            <div class="alert <?php echo (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-danger'; ?>" role="alert">
                <?php echo $mensaje; ?>
                <!-- Botón de cierre (cruz) -->
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
    </div>
      <br>
      <?php
        // Verificar si se encontraron registros de medicación para el usuario
        if (isset($resultado) && mysqli_num_rows($resultado) > 0) {
    ?>
      
    <!-- Tabla para mostrar los registros -->
        <div class="table-responsive">
            <table id="tablaPersonas" class="table table-striped table-info " >
                <thead>
                    <tr>
                        <th class=""><i class="fa-solid fa-users-line"></i> Operadores</th>
                        <th class="text-center"><i class="fas fa-image"></i> Foto</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $ruta_imagen_servidor = "img/img_medicacion/" . $fila['foto']; // Ruta de la imagen en el servidor
                    echo "<tr>";
                    echo '<td id="fila-table">
                            <p><i class="fas fa-user"></i> ' . $fila['nombre_operador'] . '</p>
                            <p><i class="fa-solid fa-calendar-days"></i>' . $fila['fecha_hora'] . '</p></td>';
                    echo '<td class="text-center"><img class="imagen_med" style="" src="' . $ruta_imagen_servidor . '"></td>';
                    echo '<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminar'.$fila['id_registro'].'">
                                <i class="fas fa-trash-alt"></i>
                                </button>
                            </div> 
                        </td>';
                    echo "</tr>";

                    // Modal de eliminación para este registro
                    echo '<div class="modal fade custom-modal" id="modalEliminar' . $fila['id_registro'] . '" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel' . $fila['id_registro'] . '" aria-hidden="true">';
                    echo '  <div class="modal-dialog modal-dialog-centered" role="document">';
                    echo '    <div class="modal-content">';
                    echo '      <div class="modal-header">';
                    echo '        <h5 class="modal-title" id="modalEliminarLabel' . $fila['id_registro'] . '">Confirmar Eliminación</h5>';
                    echo '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    echo '          <span aria-hidden="true">&times;</span>';
                    echo '        </button>';
                    echo '      </div>';
                    echo '      <div class="modal-body">';
                    echo '        ¿Está seguro de que desea eliminar este registro?';
                    echo '      </div>';
                    echo '      <div class="modal-footer">';
                    echo '        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>';
                    echo '        <a href="controller/eliminar_registro.php?id_registro=' . $fila['id_registro'] . '&id_usuarios=' . $id_usuarios . '" class="btn btn-danger">Eliminar</a>';
                    echo '      </div>';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
                }
                ?>
                </tbody>
            </table> 
        </div>
        <!-- Controles de paginación centrados -->
        <div class="modal-footer text-center">
                    <ul class="pagination">
                        <?php
                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            echo '<li class="page-item ' . ($i == $paginaActual ? 'active' : '') . '"><a class="page-link" href="?id_usuarios=' . $id_usuarios . '&pagina=' . $i . '">' . $i . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
        </div>
   

            <!-- Modal para mostrar la imagen -->
        <div id="myModal" class="modal-img">
       
        <span class="close" id="close">&times;</span>
        <img class="modal-content" id="imgModal">

        </div>
        <?php
        } else {
            echo "No se encontraron registros de medicación para el usuario y la casa especificados.";
        }
        ?>




<script>
    // Obtén todos los elementos de imagen
    var images = document.querySelectorAll('img');

    // Obtén el modal y el elemento de imagen en el modal
    var modal = document.getElementById('myModal');
    var modalImg = document.getElementById("imgModal");

    // Agrega un evento clic a cada imagen
    images.forEach(function(image) {
    image.addEventListener('click', function() {
        modal.style.display = "block";
        modalImg.src = this.src;

        // Agrega un evento clic al botón de cierre del modal
        var closeBtn = document.getElementById("close");
        closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
        });
    });
    });

    // Cierra el modal haciendo clic fuera de la imagen
    window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
    });
</script>




<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php" ?>

