
# Recibiendo datos de regristo.php

### Panel Operador

```
    <?php require_once "vistas/parte_superior.php"; ?>
            <!-- Inicio contenido principal -->
    <?php

        $mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable

        // Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
        if (isset($_GET['mensaje'])) {
            $mensaje = $_GET['mensaje'];
        }
        $id_casas = isset($_GET['id_casas']) ? $_GET['id_casas'] : null;
        $id_operadores = isset($_GET['id_operadores']) ? $_GET['id_operadores'] : null;
        $id_usuarios = isset($_GET['id_usuarios']) ? $_GET['id_usuarios'] : null;
        // Inserta los datos en una tabla temporal en la base de datos


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
        $sql = "INSERT INTO tabla_temporal  (id_casas, id_operadores, id_usuarios) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $id_casas, $id_operadores, $id_usuarios);
        $stmt->execute();

        // Recupera los datos de la tabla temporal
        $sql = "SELECT id_casas, id_operadores, id_usuarios FROM tabla_temporal ";
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $id_casas = $row['id_casas'];
            $id_operadores = $row['id_operadores'];
            $id_usuarios = $row['id_usuarios'];
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
```

### Crear tabla temporal para mantener session

> Permisos 
```
GRANT CREATE TEMPORARY TABLES, SELECT, INSERT, UPDATE, DELETE ON viviendas.* TO 'root'@'localhost';
```
> Comprobar permisos 
```
FLUSH PRIVILEGES;
```
> Crear Tabla Temproal
```
CREATE TEMPORARY TABLE tabla_temporal (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_casas INT,
  id_operadores INT,
  id_usuarios INT
);
```
> Insertar parametros para la session 

```
       $sql = "INSERT INTO tabla_temporal  (id_casas, id_operadores, id_usuarios) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $id_casas, $id_operadores, $id_usuarios);
        $stmt->execute();

        // Recupera los datos de la tabla temporal
        $sql = "SELECT id_casas, id_operadores, id_usuarios FROM tabla_temporal ";
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $id_casas = $row['id_casas'];
            $id_operadores = $row['id_operadores'];
            $id_usuarios = $row['id_usuarios'];
        }

```

* Error 
> Fatal error: Uncaught mysqli_sql_exception: Table 'vivienda.tabla_temporal' doesn't exist in C:\xampp\htdocs\sistemavivienda\vistas\inicio\panel_op.php:34 Stack trace: #0 C:\xampp\htdocs\sistemavivienda\vistas\inicio\panel_op.php(34): mysqli->prepare('INSERT INTO tab...') #1 C:\xampp\htdocs\sistemavivienda\panel_operador.php(3): include_once('C:\\xampp\\htdocs...') #2 {main} thrown in C:\xampp\htdocs\sistemavivienda\vistas\inicio\panel_op.php on line 34