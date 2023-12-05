<?php
session_start();

// Incluye la conexión a la base de datos
include "../bd/conexion.php";

// Conexión a la base de datos
$conexion = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password_users = $_POST['password_users'];

    // Realiza la verificación de la información de inicio de sesión en la base de datos
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

      // Validación de usuario y contraseña
      if (password_verify($password_users, $user['password_users'])) {
        $_SESSION['user_id'] = $user['id_users'];
        $_SESSION['user_role'] = $user['rol'];
        $_SESSION['username'] = $user['username'];
    
        // Verificar si se seleccionó "Mantener la sesión iniciada"
        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
            // Si se seleccionó "Mantener la sesión iniciada", establecer un tiempo de expiración más largo (por ejemplo, 30 días)
            session_set_cookie_params(30 * 24 * 60 * 60); // 30 días en segundos
        } else {
            // Si no se seleccionó "Mantener la sesión iniciada", utilizar el tiempo de expiración predeterminado (hasta que se cierre el navegador)
            session_set_cookie_params(0);
        }
    
        // Continuar con la redirección según el rol del usuario
        if ($user['rol'] == '1') {
            header("Location: ../panel.php?id_operadores=" . $user['id_operadores']);
            exit;
        } elseif ($user['rol'] == '2') {
            header("Location: ../index.php");
            exit;
        } else {
            $mensaje = "Tu cuenta no ha sido activada revisa tu mail";
            header("Location: ../login.php?mensaje=" . urlencode($mensaje));
            exit;
        }
    } else {
        $mensaje = "Credenciales inválidas";
        header("Location: ../login.php?mensaje=" . urlencode($mensaje));
        exit;
    }
    } else {
        
        $mensaje = "Email o contraseña incorrecto ";
            header("Location: ../login.php?mensaje=" . urlencode($mensaje));
            exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
} else {
    // Redirigir o mostrar un mensaje de error en caso de acceso directo

    $mensaje = "Acceso no permitido.";
    header("Location: ../login.php?mensaje=" . urlencode($mensaje));
    exit;
    
}
?>