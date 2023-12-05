<?php require_once "vistas/parte_superior.php"?>
<!-- Inicio contenido principal -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Casas</h1>
</div>
<div class="row">

<?php
include_once 'bd/conexion.php';

// Establecer conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Consultar los datos de la tabla "casas"
$sql = "SELECT * FROM casas";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Recorrer los resultados y mostrarlos en el HTML
    while ($row = $result->fetch_assoc()) {
        $id_casas = $row['id_casas'];
        $nombre_casa = $row['nombre_c'];
        $direccion_casa = $row['direccion_c'];
        $telefono_casa = $row['telefono_c'];

        // Mostrar los datos en el HTML
        echo '<div class="col-xl-3 col-md-6 mb-4">';
        echo '    <div class="card border-left-primary shadow h-100 py-2">';
        echo '        <div class="card-body">';
        echo '            <div class="row no-gutters align-items-center">';
        echo '                <div class="col mr-2">';
        echo '                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">';
        echo "                        Casa: $nombre_casa</div>";
        echo "                    <div class='h5 mb-0 font-weight-bold text-gray-800'>Ingresar</div>";
        echo '                </div>';
        echo '    <div class="col-auto">';
        echo "        <form action='registro.php' method='POST'>
                            <input type='hidden' name='id_casas' value='$id_casas'>
                            <button type='submit'><i class='fas fa-calendar fa-2x text-gray-300'></i></button>
                        </form>";
        echo '    </div>';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
} else {
    echo "No se encontraron datos en la tabla 'casas'.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

</div>
</div>

<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   