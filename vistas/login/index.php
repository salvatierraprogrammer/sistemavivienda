<?php
session_start();

$mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable

// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Iniciar sesión</title>
    <link rel="icon" type="image/vnd.icon" href="../../img/icons.png">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
        }

        .login-container {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .password-input-container {
            position: relative;
        }

        .password-input-container input {
            padding-right: 9px;
        }

        .password-input-container .show-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }

       

        button:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 600px) {
            .login-container {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center">
            <img src="img/icons.png" alt="" style="width: 30%; border-radius: 100%;">
        </div>
        <h2>Iniciar sesión</h2>
        <form action="controller/authenticate.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-user icon"></i> Email:</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password_users"><i class="fas fa-lock icon"></i> Contraseña:</label>
                <div class="password-input-container">
                    <input type="password" id="password_users" name="password_users" required>
                    <span class="show-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i> <!-- Icono de ojo abierto -->
                    </span>
                </div>
                <br>
                <?php if ($mensaje !== "") { ?>
                    <div class="alert <?php echo (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-danger'; ?>" role="alert">
                        <?php echo "<div class='text-center'>" . $mensaje . "</div>"; ?>
                    </div>
                <?php } ?>
            </div>
            
                <label for="remember"><input type="checkbox" id="remember" name="remember"> Mantener la sesión iniciada</label>
            <br>
            <button class="btn btn-primary" type="submit">Iniciar sesión</button>
        </form>
        <br>
        <a href="registre.php" style="text-decoration: none;
    color: #007bff;"> Crear cuenta nueva</a>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password_users");
            var showPasswordSpan = document.querySelector(".show-password");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                showPasswordSpan.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Icono de ojo cerrado
            } else {
                passwordInput.type = "password";
                showPasswordSpan.innerHTML = '<i class="fas fa-eye"></i>'; // Icono de ojo abierto
            }
        }
    </script>
    <script type="text/javascript">
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
</script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>