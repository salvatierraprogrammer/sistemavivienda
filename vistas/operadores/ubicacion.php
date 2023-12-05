<?php require_once "vistas/parte_superior.php"?>
<div class="container">
<h1>Ubicacion</h1>
<div id="map" style="height: 400px; width: 50%; margin: 0 auto; text-align: center;"></div>


<script src="../../js/script.js"></script>
<script>
    
    // Agrega el evento de carga para la API de Google Maps
    window.addEventListener("load", () => {
        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDOcMJoFTPGABxksOrd2vh9ab3nT-oMUAI&libraries=places&callback=initMap`;
        document.body.appendChild(script);
    });
</script>
<style>
    /* Estilo para el marcador */
    .marker-image {
        background-color: transparent;
        border: none;
        border-radius: 50%; /* Hace que la imagen tenga forma de círculo */
        width: 50px; /* Ajusta el tamaño de la imagen según lo desees */
        height: 50px; /* Ajusta el tamaño de la imagen según lo desees */
        padding: 0;
        margin: 0;
    }
</style>
<script>
    // Función para inicializar el mapa
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -34.6037, lng: -58.3816 }, // Coordenadas de Buenos Aires
            zoom: 10,
        });

        const data = [
            { Id_vivienda: 1, Id_operadores: 1, Fecha: "2023-07-18 10:30:00", Ubicacion: "-34.563204, -58.455764", Imagen: "../../img/imagen1.jpg" },
            // { Id_vivienda: 2, Id_operadores: 2, Fecha: "2023-07-18 11:30:00", Ubicacion: "-34.6177, -58.3691", Imagen: "../../img/imagen.png" },
        ];

        // Función para crear el símbolo personalizado
        function crearIcono(url) {
            return {
                url: url,
                scaledSize: new google.maps.Size(50, 50),
                anchor: new google.maps.Point(25, 50),
            };
        }

        data.forEach((registro) => {
            const [lat, lng] = registro.Ubicacion.split(", ");
            const posicion = { lat: parseFloat(lat), lng: parseFloat(lng) };

            const marker = new google.maps.Marker({
                position: posicion,
                map: map,
                icon: {
                    url: registro.Imagen,
                    scaledSize: new google.maps.Size(50, 50),
                    anchor: new google.maps.Point(25, 50),
                    origin: new google.maps.Point(0, 0),
                },
            });
        });
    }
</script>
</div>
<!--FIN del cont principal-->

<!-- Fincontenido -->
<?php require_once "vistas/parte_inferior.php"?>

   