<div class="card" id="card-operador">
    <div class="card-content">
    <?php
// Obtener el ID del operador de la asistencia (cambiar este valor según tus necesidades)
$id_operadores;  // Cambia esto al ID que necesitas

// Incluir el archivo de conexión a la base de datos
include_once 'bd/conexion.php';

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Consulta SQL para obtener el operador
$consulta = "SELECT nombre_o, apellido_o FROM operadores WHERE id_operadores = $id_operadores";
$resultado = $conn->query($consulta);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombreOperador = $fila['nombre_o'];
    $apellidoOperador = $fila['apellido_o'];
} else {
    $nombreOperador = "Operador no encontrado";
    $apellidoOperador = "";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

        <h2><?php echo $nombreCasa; ?></h2>
        <p><?php echo $nombreOperador . " " . $apellidoOperador; ?></p>
        <p><i class="fas fa-clock"></i> Ingreso <?php echo $horaIngreso; ?></p>
    </div>
</div>