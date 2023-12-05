### Index.php

> Si el usuario no pertence al rol menor a 2 de redirije hacia pagina panel_operador.php

```
<?php 


require_once "vistas/parte_superior.php"; ?>
<?php
 if ($userRole != 2) { // Cambiado de $userRole == 1 a $userRole != 2
    header("Location: panel_operador.php");
    exit;
}
$mensaje = ""; // Definir la variable $mensaje para evitar advertencias de Undefined variable
// Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}

    // $userRole = $_SESSION['user_role'];

   

?>


```