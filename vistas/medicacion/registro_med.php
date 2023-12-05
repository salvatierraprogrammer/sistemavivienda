<?php require_once "vistas/parte_superior.php"; ?>
<!-- Inicio contenido principal -->
<?php

$mensaje = "";

// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}

// Obtener id_usuarios y id_casas de la URL o el formulario
$id_usuarios = $_GET['id_usuarios'] ?? null;
$id_casas = $_GET['id_casas'] ?? null;

// Validar la existencia de id_usuarios
if ($id_usuarios === null) {
    echo "ID de usuarios no definido.";
    exit;
}

// Obtener el nombre del usuario según el id_usuarios
include 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

$query_usuario = "SELECT nombre_u, apellido_u FROM usuarios WHERE id_usuarios = ?";
$stmt_usuario = $conn->prepare($query_usuario);
$stmt_usuario->bind_param("i", $id_usuarios);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result();

if ($resultado_usuario && $resultado_usuario->num_rows > 0) {
    $fila_usuario = $resultado_usuario->fetch_assoc();
    $nombre_usuario = $fila_usuario['nombre_u'] . ' ' . $fila_usuario['apellido_u'];
} else {
    $nombre_usuario = "Usuario no encontrado";
}

// Obtener la fecha seleccionada del formulario
$selected_date = isset($_POST['selected_date']) ? $_POST['selected_date'] : null;

// Construir la consulta SQL
$query = "SELECT rm.id_registro, u.nombre_u, u.apellido_u, CONCAT(o.nombre_o, ' ', o.apellido_o) AS nombre_operador, 
          DATE(rm.fecha_hora) AS fecha, TIME(rm.fecha_hora) AS hora, rm.foto
          FROM registro_medicacion rm
          INNER JOIN usuarios u ON rm.id_usuarios = u.id_usuarios
          INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
          WHERE rm.id_usuarios = ?";

// Agregar la condición de id_casas si está definido
if ($id_casas !== null) {
    $query .= " AND rm.id_casas = ?";
}

// Agregar la condición de fecha si está definida
if ($selected_date !== null) {
    $query .= " AND DATE(rm.fecha_hora) = ?";
}

$query .= " ORDER BY rm.fecha_hora DESC";

// Preparar y ejecutar la consulta

// Set the number of records per page
$recordsPerPage = 3;

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the OFFSET for the SQL query
$offset = ($page - 1) * $recordsPerPage;

// Update your SQL query to include LIMIT and OFFSET
$query .= " LIMIT ? OFFSET ?";

// Preparar y ejecutar la consulta con los nuevos parámetros
$stmt = $conn->prepare($query);

// Vincular parámetros con el nuevo LIMIT y OFFSET
if ($id_casas !== null && $selected_date !== null) {
    $stmt->bind_param("issii", $id_usuarios, $id_casas, $selected_date, $recordsPerPage, $offset);
} elseif ($id_casas !== null) {
    $stmt->bind_param("isii", $id_usuarios, $id_casas, $recordsPerPage, $offset);
} elseif ($selected_date !== null) {
    $stmt->bind_param("isii", $id_usuarios, $selected_date, $recordsPerPage, $offset);
} else {
    $stmt->bind_param("iii", $id_usuarios, $recordsPerPage, $offset);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<?php

// Conectar a la base de datos
include 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Obtener la fecha seleccionada del formulario
if (isset($_POST['selected_date'])) {
    $selected_date = $_POST['selected_date'];

    // Modificar la consulta SQL para filtrar por la fecha seleccionada
    $query = "SELECT rm.id_registro, u.nombre_u, u.apellido_u, CONCAT(o.nombre_o, ' ', o.apellido_o) AS nombre_operador, 
              DATE(rm.fecha_hora) AS fecha, TIME(rm.fecha_hora) AS hora, rm.foto
              FROM registro_medicacion rm
              INNER JOIN usuarios u ON rm.id_usuarios = u.id_usuarios
              INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
              WHERE rm.id_usuarios = ? AND rm.id_casas = ?
              AND DATE(rm.fecha_hora) = ?
              ORDER BY rm.fecha_hora DESC";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $id_usuarios, $id_casas, $selected_date);
} else {
    // Consulta original sin filtro de fecha
    $query = "SELECT rm.id_registro, u.nombre_u, u.apellido_u, CONCAT(o.nombre_o, ' ', o.apellido_o) AS nombre_operador, 
              DATE(rm.fecha_hora) AS fecha, TIME(rm.fecha_hora) AS hora, rm.foto
              FROM registro_medicacion rm
              INNER JOIN usuarios u ON rm.id_usuarios = u.id_usuarios
              INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
              WHERE rm.id_usuarios = ? AND rm.id_casas = ?
              ORDER BY rm.fecha_hora DESC";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $id_usuarios, $id_casas);
}

$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado) {
    die("Error en la consulta SQL: " . mysqli_error($conn));
}

if ($resultado && mysqli_num_rows($resultado) > 0) {
    ?>
<style>


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
    .card {
    border: 1px solid #ccc;
    margin: 10px;
    padding: 10px;
    width: 300px; /* Ancho de la tarjeta */
    display: inline-block;
    vertical-align: top;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .card-content {
    padding: 10px;
    }

    .card p {
    margin: 0;
    }

    .card hr {
    margin: 5px 0;
    }

    .card-image img {
    max-width: 100%;
    height: auto;
    }
    .card-content {
    padding: 10px;
    display: flex; /* Añade esta línea para usar flexbox en card-content */
    }

    .card-image {
        flex: 1; /* Esto hace que la imagen ocupe todo el ancho disponible */
        margin-right: 10px; /* Ajusta el espacio entre la imagen y el texto */
    }

    .text-content {
        flex: 2; /* Esto hace que el contenido de texto ocupe el doble de ancho que la imagen */
    }
    .btn {
    padding: 9px 15px; /* Adjust as needed */
    font-size: 7px; /* Adjust as needed */
    margin-bottom: 7px;
 }
    /* label {
    display: block;
    font:
        1rem 'Fira Sans',
        sans-serif;
    }

    input,
    label {
    margin: 0.4rem 0;
    } */

        @media (max-width: 768px){
            /* Estilos para la imagen en el modal */
            .modal-content {
            max-width: 90%;
            max-height: 80vh;
            margin: auto;
            display: inline-block; /* Para centrar verticalmente */
            }
        }
</style>
<div class="container">
    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="container">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-pills"></i> Registro Medicación <?php echo  $nombre_usuario ?></h1>
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
            <div class="row">
                <div class="col-lg-12">
                <form id="dateForm" action="" method="POST">
                    <label for="start">Buscar por fecha:</label>
                    <input type="date" id="start" name="selected_date" value="" min="2018-01-01" max="2030-12-31" />
                    <button type="submit" class="btn-success"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <!-- <button type="button" class="btn-danger" id="resetForm">X</button> -->
                    <a  href="?id_usuarios=<?php echo $id_usuarios; ?>&id_casas=<?php echo $id_casas; ?>" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></a>
                </form>
                </div>
         </div>
    </div>
      

      
     <!-- Tabla para mostrar los registros -->
     <?php
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $ruta_imagen_servidor = "img/img_medicacion/" . $fila['foto']; // Ruta de la imagen en el servidor
        echo '<div class="card">';
        echo '<div class="card-content">';
        
        // División de la imagen al costado izquierdo
        echo '<div class="card-image">';
        echo '<img src="' . $ruta_imagen_servidor . '" alt="">';
        echo '</div>';
        
        // División del contenido de texto al costado derecho
        echo '<div class="text-content">';
        echo '<p><i class="fas fa-user"></i> ' . $fila['nombre_operador'] . '</p>';
        echo '<hr>';   
        echo '<p><i class="fas fa-clock"></i> Hora: ' . $fila['hora'] . '</p>'; 
        echo '<hr>';
        echo '<p><i class="fas fa-calendar-days"></i>Fecha: ' . $fila['fecha'] . '</p>';
        
        echo '</div>';
        
        echo '</div>'; // Fin de card-content
        echo '</div>'; // Fin de la tarjeta
    }
?>
<?php
// Display pagination links
$totalRecords = 2;
$totalPages = ceil($totalRecords / $recordsPerPage);

echo '<ul class="pagination">';
for ($i = 1; $i <= $totalPages; $i++) {
    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="?id_usuarios=' . $id_usuarios . '&id_casas=' . $id_casas . '&page=' . $i . '">' . $i . '</a></li>';
}
echo '</ul>';
?>
    </div>
    </div>
    
    <!-- Modal para mostrar la imagen -->
<div id="myModal" class="modal-img">
  <span class="close" id="close">&times;</span>
  <img class="modal-content" id="imgModal">
</div>
<?php
 } else {
    echo '<div class="alert alert-danger" role="alert" style="width: 42%; margin: 0 auto;">No se encontraron registros con la fecha indicada ';
    echo '<a href="?id_usuarios=' . $id_usuarios . '&id_casas=' . $id_casas . '" class="btn btn-primary"> <i class="fa-solid fa-arrow-left"></i> Volver</a>';
    echo '</div>';
}
?>
<script>
// Obtén todos los elementos de imagen
var images = document.querySelectorAll('img');

// Obtén el modal y el elemento de imagen en el modal
var modal = document.getElementById('myModal');
var modalImg = document.getElementById("imgModal");

// Obtén el formulario y su estado original
var dateForm = document.getElementById('dateForm');
var originalFormHTML = dateForm.innerHTML;

// Cierra el modal haciendo clic fuera de la imagen
window.addEventListener('click', function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Agrega un evento clic a cada imagen
images.forEach(function (image) {
    image.addEventListener('click', function () {
        // Muestra el modal
        modal.style.display = "block";
        // Actualiza la imagen en el modal
        modalImg.src = this.src;
    });
});


</script>




<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php" ?>