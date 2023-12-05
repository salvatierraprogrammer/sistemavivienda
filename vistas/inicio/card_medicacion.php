<?php
include_once 'bd/conexion.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}
$id_usuarios;

// Consulta SQL para recuperar la última medicación
$sql = "SELECT rm.fecha_hora, rm.foto, o.nombre_o, o.apellido_o
        FROM registro_medicacion rm
        INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
        WHERE rm.id_usuarios = $id_usuarios
        ORDER BY rm.fecha_hora DESC
        LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fecha_hora = $row["fecha_hora"];
    $foto = "img/img_medicacion/" . $row["foto"]; // Ruta corregida de la imagen
    $nombre_operador = $row["nombre_o"];
    $apellido_operador = $row["apellido_o"];
} else {
    // No se encontraron registros para el usuario
    $fecha_hora = "N/A";
    $foto = "img/placeholder.jpg"; // Ruta de la imagen de marcador de posición
    $nombre_operador = "N/A";
    $apellido_operador = "N/A";
}

$conn->close();
?>

<div class="card-flex" data-toggle="modal" data-target="#myModal" id="mostrarHistorial">
    <div class="card-content-flex">
        <div class="info">
            <h2>Última Medicación</h2>
            <p><i class="fas fa-clock"></i> <?php echo $fecha_hora; ?></p>
            <p>Responsable: <br> <?php echo $nombre_operador . " " . $apellido_operador; ?></p>
        </div>
        <div class="image">
            <img id="img" src="<?php echo $foto; ?>" alt="Medicación">
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Historial del Medicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="historialMedicacion">
                <!-- Aquí se mostrarán las últimas 5 tarjetas -->
           
            </div>
            <div class="modal-footer">
            <a type="submit" class="btn btn-success"href="registro_med.php?id_usuarios=<?php echo $id_usuarios; ?>&id_casas=<?php echo $id_casas; ?>">Historial completo</a> 
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#mostrarHistorial').click(function() {
            // Aquí se carga el historial de medicación dinámicamente
            $.ajax({
                url: 'controller/cargar_historial.php', // Archivo PHP para cargar el historial
                type: 'GET',
                data: { id_usuarios: <?php echo $id_usuarios; ?> },
                success: function(data) {
                    // Mostrar las últimas 5 tarjetas en el historial
                    $('#historialMedicacion').html(data);
                },
                error: function() {
                    $('#historialMedicacion').html('Error al cargar el historial.');
                }
            });
        });
    });
</script>