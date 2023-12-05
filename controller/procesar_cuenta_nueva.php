<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['nombre_o']) && isset($_POST['apellido_o']) && isset($_POST['email_o']) && isset($_POST['telefono_o']) && isset($_POST['password_users'])) {
        // Obtener los datos enviados desde el formulario
        $nombre_o = $_POST['nombre_o'];
        $apellido_o = $_POST['apellido_o'];
        $telefono_o = $_POST['telefono_o'];
        $email_o = $_POST['email_o'];
        $password_users = $_POST['password_users'];

        // Procesar los datos y realizar la inserción en la tabla 'operadores'
        include "../bd/conexion.php";

        $conexion = new mysqli($servername, $username, $password, $dbname);

        // Verificar si la conexión se estableció correctamente
        if ($conexion->connect_error) {
            die("Error al conectar a la base de datos: " . $conexion->connect_error);
        }

        // Crear la consulta de inserción en la tabla 'operadores' con sentencia preparada
        $consulta_operadores = "INSERT INTO operadores (nombre_o, apellido_o, email_o, telefono_o) VALUES (?, ?, ?, ?)";

        // Preparar la consulta para la tabla 'operadores'
        $stmt_operadores = $conexion->prepare($consulta_operadores);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt_operadores) {
            // Vincular los parámetros de la consulta con los datos proporcionados
            $stmt_operadores->bind_param("ssss", $nombre_o, $apellido_o, $email_o, $telefono_o);

            // Ejecutar la consulta para la tabla 'operadores'
            if ($stmt_operadores->execute()) {
                // Obtener el ID del operador recién insertado
                $id_operador = $stmt_operadores->insert_id;

                // Cifrar la contraseña antes de almacenarla en la base de datos
                $hashed_password = password_hash($password_users, PASSWORD_DEFAULT);

                // Crear la consulta de inserción en la tabla 'users' con sentencia preparada
                $consulta_users = "INSERT INTO users (id_operadores, username, email, password_users, rol) VALUES (?, ?, ?, ?, ?)";

                // Preparar la consulta para la tabla 'users'
                $stmt_users = $conexion->prepare($consulta_users);

                // Verificar si la preparación de la consulta fue exitosa
                if ($stmt_users) {
                    // Definir el rol para el usuario (puedes ajustarlo según tus necesidades)
                    $rol = 0;

                    // Vincular los parámetros de la consulta para la tabla 'users'
                    $stmt_users->bind_param("issss", $id_operador, $nombre_o, $email_o, $hashed_password, $rol);

                    // Ejecutar la consulta para la tabla 'users'
                    if ($stmt_users->execute()) {
                        // Si la consulta se ejecuta con éxito, redireccionar a la página de inicio de sesión con un mensaje de éxito
                        $mensaje = "Operador y usuario agregados exitosamente.";
                        header("Location: ../login.php?mensaje=" . urlencode($mensaje));
                        exit;
                    } else {
                        // Si hay algún error en la consulta de la tabla 'users', redireccionar con un mensaje de error
                        $mensaje = "Error al agregar el usuario: " . $stmt_users->error;
                        header("Location: ../login.php?mensaje=" . urlencode($mensaje));
                        exit;
                    }

                    // Cerrar la sentencia de la tabla 'users'
                    $stmt_users->close();
                } else {
                    // Si hubo un error en la preparación de la consulta de la tabla 'users', redireccionar con un mensaje de error
                    $mensaje = "Error al preparar la consulta de usuario: " . $conexion->error;
                    header("Location: ../login.php?mensaje=" . urlencode($mensaje));
                    exit;
                }
            } else {
                // Si hay algún error en la consulta de la tabla 'operadores', redireccionar con un mensaje de error
                $mensaje = "Error al agregar el Operador: " . $stmt_operadores->error;
                header("Location: ../login.php?mensaje=" . urlencode($mensaje));
                exit;
            }

            // Cerrar la sentencia de la tabla 'operadores'
            $stmt_operadores->close();
        } else {
            // Si hubo un error en la preparación de la consulta de la tabla 'operadores', redireccionar con un mensaje de error
            $mensaje = "Error al preparar la consulta de operador: " . $conexion->error;
            header("Location: ../login.php?mensaje=" . urlencode($mensaje));
            exit;
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    } else {
        // Si no se enviaron todos los datos necesarios, redireccionar con un mensaje de error
        $mensaje = "Error: faltan datos para agregar el operador y usuario.";
        header("Location: ../login.php?mensaje=" . urlencode($mensaje));
        exit;
    }
} else {
    // Si se intenta acceder a este archivo de forma directa sin enviar datos mediante POST, redireccionar a la página de inicio de sesión
    header("Location: ../login.php");
    exit;
}
?>