<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Asistencia</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .card {
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .styled-input {
        position: relative;
        margin-bottom: 15px;
    }

    .styled-input input {
        padding: 10px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .styled-input label {
        position: absolute;
        left: 10px;
        top: 10px;
        transition: all 0.2s ease-out;
        pointer-events: none;
    }

    .styled-input input:focus + label,
    .styled-input input:not(:placeholder-shown) + label {
        transform: translate(-5px, -25px) scale(0.75);
        background-color: #fff;
        padding: 5px;
        color: #007bff;
    }

    select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<?php





if (isset($_POST['id_casas']) && isset($_POST['id_operadores']) && isset($_POST['id_usuarios'])) {
    $id_casas = $_POST['id_casas'];
    $id_operadores = $_POST['id_operadores']; // Asegúrate de definir $id_operadores
    $id_usuarios = $_POST['id_usuarios'];
    include_once 'bd/conexion.php';

    // Establecer conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Consultar el nombre del operador utilizando su ID
    $sql = "SELECT nombre_o, apellido_o FROM operadores WHERE id_operadores = $id_operadores";
    $result = $conn->query($sql);

    // Verificar si se obtuvieron resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre_operador = $row['nombre_o'] . ' ' . $row['apellido_o'];

        echo '<div class="card">';
        echo '    <h2>Registro de Asistencia</h2>';
        echo '    <form action="controller/procesar_asistencia_op.php" method="post">';
        echo '        <div class="form-group">';
        echo '            <label for="id_operadores">Operador:</label>';
        echo "            <input class='text-center border-0' type='text' id='nombre_operador' name='nombre_operador' value='$nombre_operador' readonly>
        ";
        echo '        </div>';

        // Se obtiene la ubicación automáticamente
        echo '        <input type="hidden" name="ubicacion" id="ubicacion">';

        echo '        <input type="hidden" name="id_casas" value="' . $id_casas . '">';
        echo '        <input type="hidden" name="id_operadores" value="' . $id_operadores . '">';
        echo '        <input type="hidden" name="id_usuarios" value="' . $id_usuarios . '">';
        echo '        <button id="button-confirm" type="submit">Confirmar Asistencia</button>';
        echo '    </form>';
        echo '</div>';
    } else {
        echo "No se encontraron datos de operadores asignados a esta vivienda.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "Falta información necesaria (id_casas o id_operadores) en la URL.";
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
        const latitud = position.coords.latitude;
        const longitud = position.coords.longitude;
        console.log("Latitud:", latitud);
        console.log("Longitud:", longitud);
        const ubicacionInput = document.getElementById("ubicacion");
        ubicacionInput.value = latitud + ", " + longitud;
});
    } else {
        console.log("Geolocalización no está disponible en este navegador.");
    }
});
</script>
</body>
</html>