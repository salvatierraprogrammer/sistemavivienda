<?php require_once "vistas/parte_superior.php"; ?>
<!-- Inicio contenido principal -->
<?php
        // Inicializa las variables
        $id_casas = $id_operadores = $id_usuarios = null;

        if (isset($_COOKIE['id_casas'])) {
            $id_casas = $_COOKIE['id_casas'];
        }

        if (isset($_COOKIE['id_operadores'])) {
            $id_operadores = $_COOKIE['id_operadores'];
        }

        if (isset($_COOKIE['id_usuarios'])) {
            $id_usuarios = $_COOKIE['id_usuarios'];
        }
        $mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable

        // Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
        if (isset($_GET['mensaje'])) {
            $mensaje = $_GET['mensaje'];
        }
        if ($id_casas !== null && $id_operadores !== null) {
            // Tu código para obtener el nombre de la casa y otras operaciones aquí
        } else {
            // Manejo de errores si faltan parámetros en la URL
            echo "Faltan parámetros en la URL.";
        }

        include_once 'bd/conexion.php';
        // Realiza una consulta a la base de datos para obtener el nombre de la casa
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar si la conexión se estableció correctamente
        if ($conn->connect_error) {
            die("Error al conectar a la base de datos: " . $conn->connect_error);
        }
    
        // Suponiendo que tienes una conexión a la base de datos llamada $conn
        $sql = "SELECT nombre_c FROM casas WHERE id_casas = $id_casas";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $nombreCasa = $row['nombre_c'];
        } else {
            // Manejo de errores si la consulta falla
            $nombreCasa = "Nombre no encontrado";
        }
       // Suponiendo que ya tienes una conexión a la base de datos llamada $conn
       $sql = "SELECT nombre_u, apellido_u FROM usuarios WHERE id_usuarios = $id_usuarios";
       $result = mysqli_query($conn, $sql);
       
       if ($result) {
           $row = mysqli_fetch_assoc($result);
           $nombreUsuario = $row['nombre_u'];
           $apellidoUsuario = $row['apellido_u'];
       
           // Combina nombre y apellido en una variable
           $nombreApellido = $nombreUsuario . ' ' . $apellidoUsuario;
    
       } else {
           // Manejo de errores si la consulta falla o el usuario no se encuentra
           echo "Usuario no encontrado.";
       }
        

        // Inicializar la variable $dioAsistencia en false
        $dioAsistencia = false;

        // Consulta SQL para obtener la última asistencia del operador
            
            
        $consultaAsistenciaOperador = "SELECT * FROM asistencia WHERE id_operadores = $id_operadores AND id_vivienda = $id_casas ORDER BY id_asistencia DESC LIMIT 1";
        $resultadoAsistenciaOperador = $conn->query($consultaAsistenciaOperador);

        if ($resultadoAsistenciaOperador !== false) {
            // Verificar si la consulta se ejecutó correctamente y obtener la hora_retiro
            if ($rowAsistencia = $resultadoAsistenciaOperador->fetch_assoc()) {
                $horaRetiro = $rowAsistencia['hora_retiro'];

                // Comprobar si la hora_retiro es igual a "00:00:00"
                if ($horaRetiro === '00:00:00') {
                    $dioAsistencia = true;
                }
                // Asignar la hora de ingreso a la variable $horaIngreso
                $horaIngreso = date("H:i:s", strtotime($rowAsistencia['fecha_ingreso']));

            }

            // Liberar el resultado
            $resultadoAsistenciaOperador->free_result();
        } else {
            // Manejo de errores si la consulta SQL falla
            echo "Error en la consulta SQL: " . $conn->error;
        }


?>


<?php  include "modal.php"; ?>  



<!-- Incluye el script de WURFL -->

<script src="https://wurfl.io/wurfl.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Función para obtener el nombre del modelo del dispositivo y la ubicación
    function obtenerDatosDispositivoYUbicacion() {
        // Obtener el nombre completo del dispositivo desde WURFL
        var nombreDispositivo = WURFL.complete_device_name;
        document.getElementById("nombre_dispositivo").value = nombreDispositivo;

        // Verificar si el navegador admite la geolocalización
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function (position) {
                // Obtener las coordenadas de latitud y longitud
                var latitud = position.coords.latitude;
                var longitud = position.coords.longitude;

                // Crear una cadena con las coordenadas
                var ubicacionSalida = latitud + ", " + longitud;

                // Asignar la ubicación al campo oculto
                document.getElementById("ubicacion_salida").value = ubicacionSalida;
            });
        } else {
            // Manejo de errores si la geolocalización no está disponible
            alert("La geolocalización no está disponible en este dispositivo.");
        }
    }

    // Llama a la función cuando la página se cargue
    window.onload = obtenerDatosDispositivoYUbicacion;
    // Función para iniciar sesión
    function iniciarSesion() {
    // Lógica para iniciar sesión aquí

    // Mostrar el modal de permisos después de iniciar sesión
    $('#permisosModal').modal('show');
    }

    // Evento para aceptar permisos
    document.getElementById('aceptarPermisos').addEventListener('click', function () {
    // Aquí puedes agregar la lógica para solicitar permisos de ubicación y cámara utilizando las API del navegador.
    // Por ejemplo, para solicitar permisos de ubicación:
    navigator.geolocation.getCurrentPosition(function (position) {
        // Permisos de ubicación concedidos
        console.log('Permisos de ubicación concedidos:', position);
    }, function (error) {
        // Manejar errores de permisos de ubicación aquí
        console.error('Error de permisos de ubicación:', error);
    });

    // Para solicitar permisos de la cámara, puedes usar la API de MediaDevices
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
        // Permisos de la cámara concedidos
        console.log('Permisos de cámara concedidos:', stream);
        })
        .catch(function (error) {
        // Manejar errores de permisos de la cámara aquí
        console.error('Error de permisos de cámara:', error);
        });

    // Cierra el modal después de que se hayan solicitado los permisos
    $('#permisosModal').modal('hide');
    });
      // Esperar 3 segundos y luego ocultar el mensaje
      setTimeout(function() {
        var mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.style.display = 'none';
        }
    }, 3000); // 3000 milisegundos = 3 segundos
</script>
<script type="text/javascript">
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
</script>

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php" ?>