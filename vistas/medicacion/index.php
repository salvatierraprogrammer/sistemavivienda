<?php require_once "vistas/parte_superior.php"; ?>
<!-- Inicio contenido principal -->
<?php
    $mensaje = "";

    // Verificar si el formulario ha sido enviado y se ha agregado una nueva casa
    if (isset($_GET['mensaje'])) {
        $mensaje = $_GET['mensaje'];
    }

 
    
    if (isset($_POST['id_usuarios']) && isset($_POST['id_casas']) && isset($_POST['id_operadores']) ) { // Cambiado a $_GET si se espera obtenerlo de la URL
        $id_usuarios = $_POST['id_usuarios'];
        $id_casas = $_POST['id_casas']; // Cambiado a $_GET si se espera obtenerlo de la URL
        $id_operadores = $_POST['id_operadores'];

        // Resto de tu código aquí
    } else 
        // Manejo de error o redirección si $_GET['id_usuarios'] no está definido
    if (isset($_GET['id_usuarios'])) {
            $id_usuarios = $_GET['id_usuarios'];
            $id_casas = $_GET['id_casas'];
            $id_operadores = $_GET['id_operadores'];
        } else {
            echo "ID de usuarios no definido.";
            exit;
    }



    // Obtener el nombre del usuario según el id_usuarios
    include 'bd/conexion.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    $query_usuario = "SELECT nombre_u, apellido_u FROM usuarios WHERE id_usuarios = $id_usuarios";
    $resultado_usuario = mysqli_query($conn, $query_usuario);

    if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
        $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
        $nombre_usuario = $fila_usuario['nombre_u'] . ' ' . $fila_usuario['apellido_u'];
    } else {
        $nombre_usuario = "Usuario no encontrado";
    }
?>

<?php include_once "logica_notificar_mail.php";?>

 <style>
        /* Estilo personalizado para el video */
        #video {
            max-width: 100%;
            max-height: 100%; /* Ajusta la altura al 100% para que se adapte al modal */
            display: block;
            margin: 0 auto; /* Centra el video horizontalmente */
        }

        /* Estilo para el contenedor del video en el modal */
        #video-container {
            text-align: center;
        }
        
    .card {
        box-shadow: 10px 10px 10px rgba(51, 167, 181, 0.2);

    }

</style>
<div class="container">
   
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-pills"></i> Hs de Medicacion: <?php echo $nombre_usuario; ?></h1>
            <?php if ($mensaje !== "") { ?>
        <div class="alert <?php echo (strpos($mensaje, "Error") !== false) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
            <?php echo $mensaje; ?>
        <!-- Botón de cierre (cruz) -->
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true"> &times;</span>
        </button>
    </div>
<?php } ?>
      </div>

<div class="row">
    <?php
    
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            // Consulta para obtener las últimas fechas de medicación agregadas
            $query_ultimas_fechas = "SELECT DISTINCT DATE(um.fecha_toma) as fecha
                                    FROM usuario_medicacion um
                                    WHERE um.id_usuarios = $id_usuarios
                                    ORDER BY um.fecha_toma DESC
                                    LIMIT 1";
            
            $resultado_ultimas_fechas = mysqli_query($conn, $query_ultimas_fechas);
            
            if ($resultado_ultimas_fechas && mysqli_num_rows($resultado_ultimas_fechas) > 0) {
                $fila_ultimas_fechas = mysqli_fetch_assoc($resultado_ultimas_fechas);
                $ultima_fecha = $fila_ultimas_fechas['fecha'];
                
                // Consulta para obtener los horarios de medicación de la última fecha para el usuario
                $query_proximos_horarios = "SELECT um.id_usuarios, hm.hora_med, nm.nom_med, um.cant_mg, um.id_horario
                                            FROM usuario_medicacion um
                                            INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                                            INNER JOIN nombre_medicacion nm ON um.id_medicacion = nm.id_nom_med
                                            WHERE um.id_usuarios = $id_usuarios AND DATE(um.fecha_toma) = '$ultima_fecha'
                                            ORDER BY hm.hora_med ASC";
                
                $resultado_proximos_horarios = mysqli_query($conn, $query_proximos_horarios);
                
                if ($resultado_proximos_horarios && mysqli_num_rows($resultado_proximos_horarios) > 0) {
                    $horarios_mostrados = array();
                    while ($fila_proximo_horario = mysqli_fetch_assoc($resultado_proximos_horarios)) {
                        $hora_horario = $fila_proximo_horario['hora_med'];
                        $id_horario = $fila_proximo_horario['id_horario'];
                        
                        // Si el horario no se ha mostrado antes, crea un nuevo elemento en el array
                        if (!array_key_exists($hora_horario, $horarios_mostrados)) {
                            $horarios_mostrados[$hora_horario] = array(
                                'id_horario' => $id_horario,
                                'medicaciones' => array()
                            );
                        }
                        
                        // Agrega la medicación al array correspondiente al horario
                        $horarios_mostrados[$hora_horario]['medicaciones'][] = array(
                            'nom_med' => $fila_proximo_horario['nom_med'],
                            'cant_mg' => $fila_proximo_horario['cant_mg']
                        );
                    }
                    
                    foreach ($horarios_mostrados as $hora_horario => $data) {
                        $id_horario = $data['id_horario'];
                        $medicaciones = $data['medicaciones'];
                    
                        // Calcula el tiempo restante hasta el horario de medicación
                        $hora_actual = date('H:i'); // Hora actual en formato HH:MM
                        $hora_pasada = strtotime($hora_horario) < strtotime($hora_actual);
                    
                        // Agrega clases de color en función del tiempo restante
                        $card_classes = 'card shadow h-100 py-2';
                        if ($hora_pasada) {
                            $card_classes .= ' border-left-danger'; // Horario pasado (rojo)
                        } else {
                            $card_classes .= ' border-left-info'; // Horario futuro (azul)
                        }
                    
                        echo '<div class="col-xl-3 col-md-6 mb-4">';
                        echo '<div class="' . $card_classes . '">';
                        echo '<div class="card-body">';
                        echo '<div class="row no-gutters align-items-center">';
                        echo '<div class="col mr-2">';
                        echo '<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">';
                        echo 'Próxima medicación ' . $hora_horario . '</div>';
                        echo '</div>';
                        echo '<div class="col-auto">';
                        
                        // Aquí incluyes el enlace con el id_horario obtenido
                        echo '<a class="open-medication-modal"  data-toggle="modal" data-target="#medicationModal"
                        data-hora="' . $hora_horario . '"
                        data-id-horario="' . $id_horario . '"
                        data-usuario="' . $nombre_usuario . '"
                        data-medicacion="' . htmlentities(json_encode($medicaciones)) . '"
                        data-nombre-med="' . htmlentities($medicaciones[0]['nom_med']) . '"
                        data-cantidad-med="' . htmlentities($medicaciones[0]['cant_mg']) . '">';
                        echo '<i class="fas fa-clock fa-2x"></i>';
                        echo '</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No se encontraron horarios de medicación para la última fecha</p>';
                }
            } else {
                echo '<p>No se encontraron fechas de medicación para este usuario</p>';
            }
    
    ?>
</div>
    </div>
</div>
<div class="container">
<?php
        $query_last_medication = "SELECT um.id_user_merdicacion, nm.nom_med, um.cant_mg, hm.hora_med, DATE(um.fecha_toma) as fecha
                                FROM usuario_medicacion um
                                INNER JOIN nombre_medicacion nm ON um.id_medicacion = nm.id_nom_med
                                INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                                WHERE um.id_usuarios = $id_usuarios
                                ORDER BY um.fecha_toma DESC
                                LIMIT 1"; // Obtener solo el registro más reciente

        $resultado_last_medication = mysqli_query($conn, $query_last_medication);

        if ($resultado_last_medication && mysqli_num_rows($resultado_last_medication) > 0) {
            $fila_last_medication = mysqli_fetch_assoc($resultado_last_medication);
        ?>

            <div class="modal-footer">
              
                <h2><span class="badge bg-info text-white">
                    <i class="fas fa-calendar-alt"></i> <?php echo $fila_last_medication['fecha']; ?></span></h2>
            
            </div>
    <div class="row">
       
            

            <?php
            // Consulta para obtener todos los horarios de medicación de la fecha actual sin repetirlos
            $query_horarios = "SELECT DISTINCT hm.hora_med
                            FROM usuario_medicacion um
                            INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                            WHERE um.id_usuarios = $id_usuarios AND DATE(um.fecha_toma) = '" . $fila_last_medication['fecha'] . "'";

            $resultado_horarios = mysqli_query($conn, $query_horarios);

            if ($resultado_horarios && mysqli_num_rows($resultado_horarios) > 0) {
                while ($fila_horario = mysqli_fetch_assoc($resultado_horarios)) {
                    $hora_med = $fila_horario['hora_med'];
            ?>
            <br>
                    <div class="col-sm-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="far fa-clock"></i> <?php echo $hora_med; ?></h5>
                               <hr>
                               <?php
                                // Consulta para obtener todas las medicaciones para este horario
                                $query_medications = "SELECT um.id_user_merdicacion, nm.nom_med, um.cant_mg
                                                FROM usuario_medicacion um
                                                INNER JOIN nombre_medicacion nm ON um.id_medicacion = nm.id_nom_med
                                                INNER JOIN horarios_medicacion hm ON um.id_horario = hm.id_horario
                                                WHERE um.id_usuarios = $id_usuarios AND DATE(um.fecha_toma) = '" . $fila_last_medication['fecha'] . "' AND hm.hora_med = '$hora_med'";

                                $resultado_medications = mysqli_query($conn, $query_medications);

                                if ($resultado_medications && mysqli_num_rows($resultado_medications) > 0) {
                                    while ($fila_medication = mysqli_fetch_assoc($resultado_medications)) {
                                ?>
                                        <p class="card-text"><strong><i class="fas fa-capsules"></i>
                                            </strong> <?php echo $fila_medication['nom_med']; ?> <strong>(mg):</strong> 
                                        <?php echo $fila_medication['cant_mg']; ?></p>
                                        
                                        <hr>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<h2>No se encontraron horarios de medicación para esta fecha</h2>";
            }
        } else {
            echo "<h2>No se encontraron horarios de medicación para este usuario</h2>";
        }
        ?>
    </div>
</div>
<!-- Modal de Notificación -->
<div class="modal fade" id="modalNotificacion" tabindex="-1" role="dialog" aria-labelledby="modalNotificacionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNotificacionLabel">¡Es hora de tomar tu medicación!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Puedes mostrar detalles adicionales de la medicación aquí si es necesario -->
                <p>Es el horario correspondiente para tomar tu medicación. No olvides tomarla según las indicaciones médicas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para mostrar detalles de horario y medicación -->
<div class="modal fade" id="medicationModal" tabindex="-1" role="dialog" aria-labelledby="medicationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicationModalLabel"><i class="fas fa-prescription-bottle"></i> Detalles de Medicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="medicationModalBody">
                <p><strong><i class="fas fa-user"></i> </strong> <span id="usuarioDetalles"></span> <strong style="margin-left: 7px;"><i class="far fa-clock"></i> </strong> <span id="horaMedicacion"></span></p>
                <p></p>
                <!-- Mostrar la información de medicación -->
                <ul id="medicacionDetalles"></ul>
                <hr>
                <?php
                // Obtener el último registro de medicación para el usuario
                $query_ultimo_registro = "SELECT rm.id_registro, hm.hora_med, CONCAT(o.nombre_o, ' ', o.apellido_o) AS nombre_operador, rm.foto
                                        FROM registro_medicacion rm
                                        INNER JOIN horarios_medicacion hm ON rm.id_horario = hm.id_horario
                                        INNER JOIN operadores o ON rm.id_operadores = o.id_operadores
                                        WHERE rm.id_usuarios = $id_usuarios
                                        ORDER BY rm.fecha_hora DESC
                                        LIMIT 1";

                $resultado_ultimo_registro = mysqli_query($conn, $query_ultimo_registro);

                if ($resultado_ultimo_registro && mysqli_num_rows($resultado_ultimo_registro) > 0) {
                    $fila_ultimo_registro = mysqli_fetch_assoc($resultado_ultimo_registro);
                    $id_registro = $fila_ultimo_registro['id_registro'];
                    $hora_med = $fila_ultimo_registro['hora_med'];
                    $nombre_operador = $fila_ultimo_registro['nombre_operador'];

                    // Obtener el nombre de la imagen desde la base de datos
                    $nombre_imagen = $fila_ultimo_registro['foto'];

                    // Generar el atributo data para la imagen
                    $data_atributos = "data-hora='$hora_med' data-usuario='$nombre_operador' data-id-horario='$id_horario'";

                    // Ruta de la imagen en el servidor
                    $ruta_imagen_servidor = "img/img_medicacion/$nombre_imagen";

                    // Verificar si la imagen existe en el servidor
                    if (file_exists($ruta_imagen_servidor)) {
                        // Mostrar la imagen utilizando la ruta del servidor
                        echo "<div class='image-circle'>";
                        // echo '<img src="data:image/jpeg;base64,' . base64_encode(file_get_contents('img/img_medicacion/photo_20230907194549.png')) . '" alt="Descripción de la imagen">';
                        echo "<img id='imagenConfirmacion' src='$ruta_imagen_servidor' alt='Imagen de confirmación' $data_atributos class='image-circle'>";
                        echo "</div>";
                        echo "<br>";
                        echo "<p><strong><i class='fas fa-tablets'></i> Última medicación digerida:</strong> <span id='ultimaHoraMedicacion'>$hora_med</span></p>";
                        echo "<p><strong><i class='fas fa-user'></i> Operador responsable:</strong></strong> 
                                    <span id='nombreOperador'>$nombre_operador</span>
                                    </p>";
                    
                        echo "<hr>";
                    } else {
                        echo "<p>La imagen no se encontró en el servidor.</p>";
                    }
                } else {
                    echo "<p>No se encontraron registros de medicación para este usuario.</p>";
                }
                ?>
                
                <br>
                <form action="controller/procesar_medicacion_img.php" method="POST" enctype="multipart/form-data">
               
                <div class="image-capture"  >
                <!-- <img id="photo" name="foto" alt="La foto capturada aparecerá aquí" style="display:none;"> -->
                <canvas name="foto" id="canvas" width="640" height="480">Aqui</canvas>
                
                </div>                
                <input type="hidden" name="imagen_base64" id="imagenBase64">
                <!-- <input type="file" name="foto" id="canvas"> -->
                <input type="hidden" name="id_horario" value="" id="idHorarioInput">
                <input type="hidden" name="id_casas" value="<?php echo $id_casas ?>">
                <input type="hidden" name="id_usuarios" id="idUsuarioHidden" value="<?php echo $id_usuarios ?>">
               
                <br>
                <?php
                    if (isset($id_operadores)) {
                        // Consulta para obtener el nombre del operador
                        $query = "SELECT nombre_o, apellido_o FROM operadores WHERE id_operadores = $id_operadores";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $nombre_operador = $row['nombre_o'] . ' ' . $row['apellido_o'];
                            echo '<select id="operadorSelector" name="id_operadores" class="form-control" required>';
                            echo "<option value='$id_operadores'>$nombre_operador</option>";
                            echo '</select>';
                        } else {
                            echo '<p>No se encontró el operador con el ID proporcionado.</p>';
                        }

                        // Cerrar la conexión a la base de datos
                        $conn->close();
                    } else {
                        echo '<p>El ID de operador no está definido.</p>';
                    }
                    ?>
                <br>              
                <div class="modal-footer">  
                
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar reporte
                    </button>
                </div>
                </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                <button id="activarCamara" class="btn btn-primary"><i class="fas fa-camera"></i> Tomar foto</button>
            </div>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal para la cámara -->
<div class="modal fade" id="cameraModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cámara</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="video-container"> <!-- Contenedor del video -->
                    <video id="video" autoplay></video>
                </div>
                <!-- <img id="photo" alt="La foto capturada aparecerá aquí" style="display:none;"> -->
                <br>
                <!-- Botón para guardar la foto y cerrar el modal -->
                <div class=" text-center">
               
                <button id="guardarFoto" style="width: 50%; height: 50%;" class="btn btn-secondary"><i class="fa-solid fa-camera"></i></button>
              </div>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function () {
        // Variable para controlar si se ha mostrado la notificación
        let notificacionMostrada = false;

        // Obtener la hora actual en formato HH:MM
        const horaActual = new Date().toLocaleTimeString('en-US', { hour12: false, hour: 'numeric', minute: 'numeric' });

        // Obtener la hora de la próxima medicación (de tu lógica actual)
        const horaProximaMedicacion = '11:39'; // Asignar la hora real aquí

        // Comprobar si la hora actual coincide con la hora de la próxima medicación
        if (horaActual === horaProximaMedicacion) {
            // Mostrar la notificación si es la hora correcta
            $('#modalNotificacion').modal('show');
            notificacionMostrada = true;
        }

        // Si deseas mostrar la notificación nuevamente después de un período de tiempo, puedes usar un temporizador
        // Por ejemplo, para mostrar la notificación cada hora (ajusta según tus necesidades)
        const intervaloNotificacion = 60 * 60 * 1000; // 1 hora en milisegundos
        setInterval(function () {
            // Verificar nuevamente si es la hora de la próxima medicación
            if (!notificacionMostrada && horaActual === horaProximaMedicacion) {
                $('#modalNotificacion').modal('show');
                notificacionMostrada = true;
            }
        }, intervaloNotificacion);
      
        setInterval(verificarHora, 60000); // 60000 milisegundos = 1 minuto
    });
</script>

<script>
    
    $(document).ready(function () {
        // Variable para controlar si la cámara está activa o no
        let isCameraActive = false;

        // Evento al hacer clic en el enlace "open-medication-modal"
        $('.open-medication-modal').click(function () {
            // Obtener los datos necesarios del atributo data
            const horaMedicacion = $(this).data('hora');
            const usuarioDetalles = $(this).data('usuario');
            const medicaciones = $(this).data('medicacion'); // Lista de medicaciones
            const idHorario = $(this).data('id-horario');

            // Crear el HTML para la lista de medicaciones y cantidades
            let medicacionesHtml = '<h5><i class="fas fa-capsules"></i> Medicación</h5><ul>';
            medicaciones.forEach(med => {
                medicacionesHtml += '<li><strong><i class="fas fa-tablets"></i> ' + med.nom_med + '</strong>  ' + med.cant_mg + ' MG</li>';
            });
            medicacionesHtml += '</ul>';

            // Insertar los detalles en el modal
            $('#horaMedicacion').text(horaMedicacion);
            $('#usuarioDetalles').text(usuarioDetalles);
            $('#medicacionDetalles').html(medicacionesHtml);

            // Actualizar el valor del campo de entrada
            $('#idHorarioInput').val(idHorario);

            // Abrir el modal
            $('#medicationModal').modal('show');
        });

        // Evento al hacer clic en el botón "Activar Cámara"
        $('#activarCamara').click(function () {
            $('#cameraModal').modal('show'); // Mostrar el modal de la cámara

            // Configurar las opciones para la cámara trasera
            const videoConstraints = {
                facingMode: 'environment', // Cámara trasera
                video: true
            };

            navigator.mediaDevices.getUserMedia({ video: videoConstraints })
                .then(function(stream) {
                    const video = document.getElementById('video');
                    video.srcObject = stream;
                    video.play();
                    isCameraActive = true;
                })
                .catch(function(err) {
                    console.error('Error al acceder a la cámara: ' + err);
                });
        });

    
        // Evento al hacer clic en el botón "Guardar Foto"
        $('#guardarFoto').click(function () {
            if (isCameraActive) {
                // Capturar la foto en el canvas
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                const video = document.getElementById('video');

                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convertir la imagen del canvas a formato base64
                const imageData = canvas.toDataURL('image/png');

                // Asignar los datos base64 al campo oculto en el formulario
                $('#imagenBase64').val(imageData.replace(/^data:image\/(png|jpeg);base64,/, ''));

                // Ocultar el segundo modal y mostrar el primer modal
                $('#cameraModal').modal('hide');
                $('#medicationModal').modal('show');

                // Restablecer la cámara y el botón "Activar Cámara"
                video.srcObject.getTracks().forEach(track => track.stop());
                $('#activarCamara').prop('disabled', false);
                isCameraActive = false;
            }
        });

        // Evento al hacer clic en el botón "Capturar Foto"
        $('#capture').click(function () {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            const photo = document.getElementById('photo');

            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            photo.src = canvas.toDataURL('image/png');
            photo.style.display = 'block';
        });
    });
    
</script>


<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php" ?>