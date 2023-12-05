<?php
// Conexión a la base de datos (debes modificar los detalles de conexión según tu configuración)
include "bd/conexion.php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Obtener el ID de la casa desde la solicitud POST
if (isset($_POST['id_casas'])) {
    $id_casas = $_POST['id_casas'];

    // Realiza una consulta para obtener los usuarios de una casa específica
    $consultaUsuariosCasa = "SELECT id_usuarios, nombre_u, apellido_u FROM usuarios WHERE id_casas = ?";
    // Preparar la consulta
    $stmt = $conn->prepare($consultaUsuariosCasa);
    // Vincular el parámetro
    $stmt->bind_param("i", $id_casas);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener resultados
    $result = $stmt->get_result();

    // Crear un array para almacenar los usuarios
    $usuarios = array();

    // Recorrer los resultados y agregarlos al array de usuarios
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = array(
            'id_usuarios' => $row['id_usuarios'],
            'nombre_u' => $row['nombre_u'],
            'apellido_u' => $row['apellido_u']
        );
    }

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conn->close();

    // Devolver la lista de usuarios en formato JSON
    echo json_encode($usuarios);
} else {
    // En caso de que no se proporcione el ID de la casa
    echo json_encode(array('error' => 'ID de casa no proporcionado'));
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
