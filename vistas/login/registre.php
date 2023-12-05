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
    <title>Registrarse</title>
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
        <h2>Crear cuenta nueva</h2>
        <form action="controller/procesar_cuenta_nueva.php" method="POST">
            
            <div class="form-group">
                <label for="nombre_o"><i class="fas fa-user icon"></i> Nombre:</label>
                <input type="text" id="nombre_o" name="nombre_o" placeholder="Escriba su nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido_o"><i class="fas fa-user icon"></i> Apellido:</label>
                <input type="text" id="apellido_o" name="apellido_o" placeholder="Escriba su apellido" required>
            </div>
            <div class="form-group">
                <label for="telefono_o"><i class="fas fa-phone icon"></i> Celular:</label>
                <input type="text" id="telefono_o" name="telefono_o" placeholder="Escriba su celular ejemplo: 1132xx90xx" required>
            </div>
            <hr>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope icon"></i> Email:</label>
                <input type="text" id="email_o" name="email_o" placeholder="Escriba su email" required>
            </div>
            <div class="form-group">
                <label for="password_users"><i class="fas fa-lock icon"></i> Crear contraseña:</label>
                <div class="password-input-container">
                    <input type="password" id="password_users" name="password_users" placeholder="Escriba su contraseña" required>
                    <span class="show-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i> <!-- Icono de ojo abierto -->
                    </span>
                </div>
                <br>
                
            </div>
            
                
            <br>
            <button class="btn btn-primary" type="submit">Crear cuenta</button>
        </form>
        <br>
        <a href="login.php" style="text-decoration: none;
    color: #007bff;"> Ya tienes cuenta? Iniciar Session</a>
     

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
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>