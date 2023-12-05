<div class="card" id="card-operador">
    <div class="card-content">
      <?php
          $consulta = "SELECT nombre_o, apellido_o FROM operadores WHERE id_operadores = $id_operadores";
          $resultado = $conn->query($consulta);

          if ($resultado->num_rows > 0) {
              $fila = $resultado->fetch_assoc();
              $nombreOperador = $fila['nombre_o'];
              $apellidoOperador = $fila['apellido_o'];
          } else {
              $nombreOperador = "Operador no encontrado";
              $apellidoOperador = "";
          }
        ?>
        <h2><i class="fas fa-home"></i> <?php echo $nombreCasa; ?></h2>
        <p><i class="fas fa-user"></i> <?php echo $nombreOperador . " " . $apellidoOperador; ?></p>
        <p><i class="fas fa-clock"></i> Ingreso <?php echo $horaIngreso; ?> <span class="badge bg-danger text-white">Finalizar jornada</span></p>
    </div>
</div>
<!-- <div class="card" id="card-opciones">
    <div class="card-content">
        <h2>Opciones</h2>
        <button  class="btn btn-danger" data-toggle="modal" data-target="#miModal">Finalizar jornada laboral</button>
    </div>
</div> -->
<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miModalLabel">Terminar jornada laboral</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de terminar la jornada laboral?
            </div>
            <div class="modal-footer justify-content-center">
              <div class="row justify-content-center">
                  <div class="col-auto">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Cancelar </button>
                  </div>
                  <div class="col-auto">
                      <form action="controller/finalizar_jornada.php" method="POST">
                        <input type="hidden" name="id_operadores" value="<?php echo $id_operadores; ?>"/>
                        <input type="hidden" name="id_casas" value="<?php echo $id_casas; ?>"/>
                        <input type="hidden" id="nombre_dispositivo" name="nombre_dispositivo" value="">
                        <input type="hidden" id="ubicacion_salida" name="ubicacion_salida" value="">
                        <button type="submit" id="buttonFinalizar" class="btn btn-danger" data-toggle="modal" data-target="#miModal">
                        <i class="fas fa-check"></i> Finalizar </button>
                    </form>                 
                    </div>
              </div>
          </div>
        </div>
    </div>
</div>
<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Contenido del modal...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- Otros botones del pie del modal si es necesario -->
      </div>
    </div>
  </div>
</div>
<script src="https://wurfl.io/wurfl.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.getElementById('buttonFinalizar').addEventListener('click', function (event) {
    // Muestra un cuadro de diálogo de confirmación
    var confirmacion = confirm('¿Estás seguro de que deseas finalizar tu jornada laboral?');

    // Si el usuario hace clic en "Cancelar", detén el envío del formulario
    if (!confirmacion) {
        event.preventDefault();
    }
});
$(document).ready(function(){
    $("#card-operador").click(function(){
      $("#miModal").modal('show');
    });
  });
</script>
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
</script>

